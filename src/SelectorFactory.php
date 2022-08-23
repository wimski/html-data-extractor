<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor;

use DOMAttr;
use DOMNamedNodeMap;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\Contracts\PlaceholderExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\SelectorFactoryInterface;

class SelectorFactory implements SelectorFactoryInterface
{
    public function __construct(
        protected PlaceholderExtractorInterface $placeholderExtractor,
    ) {
    }

    public function make(Crawler $html): string
    {
        /** @var DOMNode $node */
        $node = $html->getNode(0);

        $selector = $this->makeSelectorForNode($node);

        foreach ($html->ancestors() as $ancestor) {
            $selector = $this->makeSelectorForNode($ancestor) . ' > ' . $selector;
        }

        return $selector;
    }

    protected function makeSelectorForNode(DOMNode $node): string
    {
        $selector = $node->nodeName;

        /** @var DOMNamedNodeMap $attributes */
        $attributes = $node->attributes;

        /** @var DOMAttr $attribute */
        foreach ($attributes as $attribute) {
            $selector .= $this->makeSelectorForAttribute($attribute);
        }

        return $selector;
    }

    protected function makeSelectorForAttribute(DOMAttr $attribute): string
    {
        if ($attribute->name === 'class') {
            return '.' . str_replace(' ', '.', $attribute->value);
        }

        if ($attribute->name === 'id') {
            return "#{$attribute->value}";
        }

        $selector = "[{$attribute->name}";

        if (! empty($attribute->value) && ! $this->placeholderExtractor->extract($attribute->value)) {
            $selector .= "='{$attribute->value}'";
        }

        return $selector . ']';
    }
}
