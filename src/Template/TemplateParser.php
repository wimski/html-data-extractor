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

        $domNode = $this->templateRootNodeExtractor->extract($template);

        return $this->makeTemplateNode($domNode);
    }

    /**
     * @param DOMNode $domNode
     * @return TemplateNodeInterface
     * @throws TemplateParsingException
     */
    protected function makeTemplateNode(DOMNode $domNode): TemplateNodeInterface
    {
        $templateNode = new TemplateNode(
            $this->selectorFactory->make($domNode),
        );

        $data = $this->templateDataExtractor->extract($domNode);

        foreach ($data as $item) {
            try {
                $templateNode->addData($item);
            } catch (TemplateNodeDataAlreadyExistsException $exception) {
                throw new TemplateParsingException($exception);
            }
        }

        $firstChild = $domNode->firstChild;

        if ($firstChild) {
            $this->parseNode($firstChild, $templateNode);
        }

        return $templateNode;
    }

    /**
     * @param DOMNode               $domNode
     * @param TemplateNodeInterface $parent
     * @return void
     * @throws TemplateParsingException
     */
    protected function parseNode(DOMNode $domNode, TemplateNodeInterface $parent): void
    {
        if ($domNode->nodeType !== XML_ELEMENT_NODE) {
            $this->parseNextTemplateNode($domNode, $parent);

            return;
        }

        $templateNode = $this->makeTemplateNode($domNode);

        try {
            $parent->addChild($templateNode);
        } catch (TemplateNodeChildAlreadyExistsException $exception) {
            throw new TemplateParsingException($exception);
        }

        $domNode = $this->markTemplateNodeAsGroup($domNode, $templateNode);

        $this->parseNextTemplateNode($domNode, $parent);
    }

    protected function markTemplateNodeAsGroup(DOMNode $domNode, TemplateNodeInterface $templateNode): DOMNode
    {
        $previous = $domNode->previousSibling;

        if (! $previous || $previous->nodeType !== XML_TEXT_NODE) {
            return $domNode;
        }

        /** @var string $nodeValue */
        $nodeValue = $previous->nodeValue;

        $match = $this->groupMatcher->getGroupStartMatch($nodeValue);

        if (! $match) {
            return $domNode;
        }

        $templateNode->makeGroup($match->getPartialMatch());

        /** @var DOMNode $next */
        $next = $domNode->nextSibling;

        return $next;
    }

    /**
     * @param DOMNode               $node
     * @param TemplateNodeInterface $parent
     * @return void
     * @throws TemplateParsingException
     */
    protected function parseNextTemplateNode(DOMNode $node, TemplateNodeInterface $parent): void
    {
        $nextNode = $node->nextSibling;

        if (! $nextNode) {
            return;
        }

        $this->parseNode($nextNode, $parent);
    }
}
