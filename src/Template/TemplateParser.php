<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\Factories\SelectorFactoryInterface;
use Wimski\HtmlDataExtractor\Contracts\Matching\GroupMatcherInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateDataExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateParserInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateRootNodeExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateValidatorInterface;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeChildAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeDataAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateParsingException;

class TemplateParser implements TemplateParserInterface
{
    public function __construct(
        protected TemplateValidatorInterface $validator,
        protected GroupMatcherInterface $groupMatcher,
        protected SelectorFactoryInterface $selectorFactory,
        protected TemplateRootNodeExtractorInterface $templateRootNodeExtractor,
        protected TemplateDataExtractorInterface $templateDataExtractor,
    ) {
    }

    public function parse(string $template): TemplateNodeInterface
    {
        $this->validator->validate($template);

        $node = $this->templateRootNodeExtractor->extract($template);

        return $this->makeTemplateNode($node);
    }

    /**
     * @param DOMNode $node
     * @return TemplateNodeInterface
     * @throws TemplateParsingException
     */
    protected function makeTemplateNode(DOMNode $node): TemplateNodeInterface
    {
        $templateNode = new TemplateNode(
            $this->selectorFactory->make($node),
        );

        $data = $this->templateDataExtractor->extract($node);

        foreach ($data as $item) {
            try {
                $templateNode->addData($item);
            } catch (TemplateNodeDataAlreadyExistsException $exception) {
                throw new TemplateParsingException($exception);
            }
        }

        $firstChild = $node->firstChild;

        if ($firstChild) {
            $this->parseNode($firstChild, $templateNode);
        }

        return $templateNode;
    }

    /**
     * @param DOMNode               $node
     * @param TemplateNodeInterface $parent
     * @return void
     * @throws TemplateParsingException
     */
    protected function parseNode(DOMNode $node, TemplateNodeInterface $parent): void
    {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            $this->parseNextTemplateNode($node, $parent);
            return;
        }

        $templateNode = $this->makeTemplateNode($node);

        try {
            $parent->addChild($templateNode);
        } catch (TemplateNodeChildAlreadyExistsException $exception) {
            throw new TemplateParsingException($exception);
        }

        $previous = $node->previousSibling;

        if (! $previous || $previous->nodeType !== XML_TEXT_NODE) {
            $this->parseNextTemplateNode($node, $parent);
            return;
        }

        /** @var string $nodeValue */
        $nodeValue = $previous->nodeValue;

        $match = $this->groupMatcher->getGroupStartMatch($nodeValue);

        if (! $match) {
            $this->parseNextTemplateNode($node, $parent);
            return;
        }

        $templateNode->makeGroup($match->getPartialMatch());

        $nextNode = $node->nextSibling;

        if (! $nextNode) {
            return;
        }

        $this->parseNextTemplateNode($nextNode, $parent);
    }

    protected function parseNextTemplateNode(DOMNode $node, TemplateNodeInterface $parent): void
    {
        $nextNode = $node->nextSibling;

        if (! $nextNode) {
            return;
        }

        $this->parseNode($nextNode, $parent);
    }
}
