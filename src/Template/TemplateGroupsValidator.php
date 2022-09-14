<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\HtmlLoaderInterface;
use Wimski\HtmlDataExtractor\Contracts\Matching\GroupMatcherInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateValidatorInterface;
use Wimski\HtmlDataExtractor\Exceptions\TemplateValidationException;

class TemplateGroupsValidator implements TemplateValidatorInterface
{
    public function __construct(
        protected HtmlLoaderInterface $htmlLoader,
        protected GroupMatcherInterface $groupMatcher,
    ) {
    }

    public function validate(string $template): void
    {
        $html = $this->htmlLoader->load($template);

        $this->validateChildren($html);
    }

    /**
     * @param DOMNode $node
     * @return void
     * @throws TemplateValidationException
     */
    protected function validateChildren(DOMNode $node): void
    {
        $firstChild = $node->firstChild;

        if ($firstChild) {
            $this->validatePerNode($firstChild);
        }
    }

    /**
     * @param DOMNode $node
     * @return void
     * @throws TemplateValidationException
     */
    protected function validatePerNode(DOMNode $node): void
    {
        if ($node->nodeType === XML_TEXT_NODE) {
           $node = $this->validateTextNode($node);
        }

        if ($node->nodeType === XML_ELEMENT_NODE) {
            $this->validateChildren($node);
        }

        $next = $node->nextSibling;

        if ($next) {
            $this->validatePerNode($next);
        }
    }

    /**
     * @param DOMNode $textNode
     * @return DOMNode
     * @throws TemplateValidationException
     */
    protected function validateTextNode(DOMNode $textNode): DOMNode
    {
        /** @var string $text */
        $text = $textNode->nodeValue;

        if ($this->groupMatcher->matchesGroupEnd($text)) {
            throw new TemplateValidationException('Group end tag found without a group start tag');
        }

        // If not a group start tag
        // then just continue
        if (! $this->groupMatcher->matchesGroupStart($text)) {
            return $textNode;
        }

        $elementNode = $textNode->nextSibling;

        // A group start tag
        // must always be followed
        // by an element node
        if (! $elementNode || $elementNode->nodeType !== XML_ELEMENT_NODE) {
            throw new TemplateValidationException('Missing element node after group start tag');
        }

        $this->validateChildren($elementNode);

        $groupEndNode = $elementNode->nextSibling;

        // The element node
        // must always be followed
        // by a group end tag
        if (! $groupEndNode || ! $this->nodeIsGroupEnd($groupEndNode)) {
            throw new TemplateValidationException('Missing group end tag after element node');
        }

        return $groupEndNode;
    }

    protected function nodeIsGroupEnd(DOMNode $node): bool
    {
        return $node->nodeType === XML_TEXT_NODE
            && $node->nodeValue
            && $this->groupMatcher->matchesGroupEnd($node->nodeValue);
    }
}
