<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Factories;

use DOMAttr;
use DOMNamedNodeMap;
use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\Factories\SelectorFactoryInterface;
use Wimski\HtmlDataExtractor\Contracts\Matching\PlaceholderMatcherInterface;

class SelectorFactory implements SelectorFactoryInterface
{
    public function __construct(
        protected PlaceholderMatcherInterface $placeholderMatcher,
    ) {
    }

    public function make(DOMNode $node): string
    {
        $selector   = $node->nodeName;
        $attributes = $this->getNodeAttributes($node);

        ksort($attributes);

        foreach ($attributes as $name => $value) {
            $selector .= $this->makeSelectorForAttribute($name, $value);
        }

        return $selector;
    }

    /**
     * @param DOMNode $node
     * @return array<string, string>
     */
    protected function getNodeAttributes(DOMNode $node): array
    {
        $attributes = [];

        /** @var DOMNamedNodeMap $nodeAttributes */
        $nodeAttributes = $node->attributes;

        /** @var DOMAttr $attribute */
        foreach ($nodeAttributes as $attribute) {
            $attributes[$attribute->name] = $attribute->value;
        }

        return $attributes;
    }

    protected function makeSelectorForAttribute(string $name, string $value): string
    {
        if ($name === 'class') {
            return '.' . str_replace(' ', '.', $value);
        }

        if ($name === 'id') {
            return "#{$value}";
        }

        $selector = "[{$name}";

        if (! empty($value) && ! $this->placeholderMatcher->matchesPlaceholder($value)) {
            $selector .= "='{$value}'";
        }

        return $selector . ']';
    }
}
