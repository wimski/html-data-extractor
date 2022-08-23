<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor;

use DOMAttr;
use DOMNamedNodeMap;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\Contracts\ExtractableFinderInterface;
use Wimski\HtmlDataExtractor\Contracts\ExtractableInterface;
use Wimski\HtmlDataExtractor\Contracts\PlaceholderExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\SelectorFactoryInterface;
use Wimski\HtmlDataExtractor\Enums\ExtractableTypeEnum;

class ExtractableFinder implements ExtractableFinderInterface
{
    public function __construct(
        protected PlaceholderExtractorInterface $placeholderExtractor,
        protected SelectorFactoryInterface $selectorFactory,
    ) {
    }

    public function find(string $html): array
    {
        return $this->findExtractables(
            new Crawler($html),
        );
    }

    /**
     * @param Crawler $html
     * @return array<int, ExtractableInterface>
     */
    protected function findExtractables(Crawler $html): array
    {
        $extractables = [];

        $html->each(function (Crawler $item) use (&$extractables): void {
            $extractables = array_merge(
                $extractables,
                $this->getExtractablesForItem($item),
            );

            $children = $item->children();

            if ($children->count()) {
                $extractables = array_merge(
                    $extractables,
                    $this->findExtractables($children),
                );
            }
        });

        return $extractables;
    }

    /**
     * @param Crawler $item
     * @return array<int, ExtractableInterface>
     */
    protected function getExtractablesForItem(Crawler $item): array
    {
        $extractables = [];

        $textExtractable = $this->getTextExtractable($item);

        if ($textExtractable) {
            $extractables[] = $textExtractable;
        }

        return array_merge(
            $extractables,
            $this->getAttributeExtractables($item),
        );
    }

    protected function getTextExtractable(Crawler $item): ?ExtractableInterface
    {
        /** @var DOMNode $node */
        $node = $item->getNode(0);

        $firstChild = $node->firstChild;

        if (! $firstChild) {
            return null;
        }

        if ($firstChild->nodeType !== XML_TEXT_NODE) {
            return null;
        }

        $placeholder = $this->placeholderExtractor->extract($firstChild->textContent);

        if (! $placeholder) {
            return null;
        }

        return new Extractable(
            ExtractableTypeEnum::TEXT,
            $placeholder,
            $this->selectorFactory->make($item),
        );
    }

    /**
     * @param Crawler $item
     * @return array<int, ExtractableInterface>
     */
    protected function getAttributeExtractables(Crawler $item): array
    {
        $extractables = [];

        /** @var DOMNode $node */
        $node = $item->getNode(0);

        /** @var DOMNamedNodeMap $attributes */
        $attributes = $node->attributes;

        /** @var DOMAttr $attribute */
        foreach ($attributes as $attribute) {
            $placeholder = $this->placeholderExtractor->extract($attribute->value);

            if (! $placeholder) {
                continue;
            }

            $extractables[] = new Extractable(
                ExtractableTypeEnum::ATTRIBUTE,
                $placeholder,
                $this->selectorFactory->make($item),
                $attribute->name,
            );
        }

        return $extractables;
    }
}
