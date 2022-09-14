<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use DOMAttr;
use DOMNamedNodeMap;
use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\Matching\PlaceholderMatcherInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateAttributeDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateTextDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateDataExtractorInterface;
use Wimski\HtmlDataExtractor\Template\Data\TemplateAttributeData;
use Wimski\HtmlDataExtractor\Template\Data\TemplateTextData;

class TemplateDataExtractor implements TemplateDataExtractorInterface
{
    public function __construct(
        protected PlaceholderMatcherInterface $placeholderMatcher,
    ) {
    }

    public function extract(DOMNode $node): array
    {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return [];
        }

        return array_merge(
            $this->getTextData($node),
            $this->getAttributeData($node),
        );
    }

    /**
     * @param DOMNode $node
     * @return array<int, TemplateTextDataInterface>
     */
    protected function getTextData(DOMNode $node): array
    {
        $data = [];

        /** @var DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeType !== XML_TEXT_NODE) {
                continue;
            }

            $match = $this->placeholderMatcher->getPlaceholderMatch($childNode->textContent);

            if (! $match) {
                continue;
            }

            $data[] = new TemplateTextData($match->getPartialMatch());
        }

        return $data;
    }

    /**
     * @param DOMNode $node
     * @return array<int, TemplateAttributeDataInterface>
     */
    protected function getAttributeData(DOMNode $node): array
    {
        $data = [];

        /** @var DOMNamedNodeMap $attributes */
        $attributes = $node->attributes;

        /** @var DOMAttr $attribute */
        foreach ($attributes as $attribute) {
            $match = $this->placeholderMatcher->getPlaceholderMatch($attribute->value);

            if (! $match) {
                continue;
            }

            $data[] = new TemplateAttributeData(
                $match->getPartialMatch(),
                $attribute->name,
            );
        }

        return $data;
    }
}
