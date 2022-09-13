<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\Factories\SelectorFactoryInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\GroupMatcherInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateDataExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateParserInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateRootNodeExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateValidatorInterface;

class TemplateParser implements TemplateParserInterface
{
    public function __construct(
        protected TemplateValidatorInterface $validator,
        protected SelectorFactoryInterface $selectorFactory,
        protected GroupMatcherInterface $groupMatcher,
        protected TemplateDataExtractorInterface $templateDataExtractor,
        protected TemplateRootNodeExtractorInterface $templateRootNodeExtractor,
    ) {
    }

    public function parse(string $template): TemplateNodeInterface
    {
        $this->validator->validate($template);

        $node = $this->templateRootNodeExtractor->extract($template);

        return $this->makeTemplateNode($node);
    }

    protected function makeTemplateNode(DOMNode $node): TemplateNodeInterface
    {
        $templateNode = new TemplateNode(
            $this->selectorFactory->make($node),
        );

        $data = $this->templateDataExtractor->extract($node);

        foreach ($data as $item) {
            $templateNode->addData($item);
        }

        $firstChild = $node->firstChild;

        if ($firstChild) {
            $this->parseNode($firstChild, $templateNode);
        }

        return $templateNode;
    }

    protected function parseNode(DOMNode $node, TemplateNodeInterface $parent): void
    {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            $this->parseNextTemplateNode($node, $parent);
            return;
        }

        $templateNode = $this->makeTemplateNode($node);

        $templateNode->setParent($parent);
        $parent->addChild($templateNode);

        $previous = $node->previousSibling;

        if (! $previous || $previous->nodeType !== XML_TEXT_NODE) {
            $this->parseNextTemplateNode($node, $parent);
            return;
        }

        /** @var string $nodeValue */
        $nodeValue = $previous->nodeValue;

        $matches = $this->groupMatcher->getGroupStartMatches($nodeValue);

        if (empty($matches)) {
            $this->parseNextTemplateNode($node, $parent);
            return;
        }

        $templateNode->makeGroup($matches[0]->getPartialMatch());

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
