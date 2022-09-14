<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use DOMAttr;
use DOMNamedNodeMap;
use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\Matching\PlaceholderMatcherInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateAttributeDataInterface;
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
        $data = [];

        $textData = $this->getTextData($node);

        if ($textData) {
            $data[] = $textData;
        }

        return array_merge(
            $data,
            $this->getAttributesData($node),
        );
    }

    protected function getTextData(DOMNode $node): ?\Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateTextDataInterface
    {
        $firstChild = $node->firstChild;

        if (! $firstChild) {
            return null;
        }

        if ($firstChild->nodeType !== XML_TEXT_NODE) {
            return null;
        }

        $match = $this->placeholderMatcher->getPlaceholderMatch($firstChild->textContent);

        if (! $match) {
            return null;
        }

        return new TemplateTextData($match->getPartialMatch());
    }

    /**
     * @param DOMNode $node
     * @return array<int, \Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateAttributeDataInterface>
     */
    protected function getAttributesData(DOMNode $node): array
    {
        $placeholders = [];

        /** @var DOMNamedNodeMap $attributes */
        $attributes = $node->attributes;

        /** @var DOMAttr $attribute */
        foreach ($attributes as $attribute) {
            $match = $this->placeholderMatcher->getPlaceholderMatch($attribute->value);

            if (! $match) {
                continue;
            }

            $placeholders[] = new TemplateAttributeData(
                $match->getPartialMatch(),
                $attribute->name,
            );
        }

        return $placeholders;
    }
}
