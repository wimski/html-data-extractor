<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Source;

use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\Contracts\Source\SourceGroupInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\SourceParserInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\SourceRowInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateAttributeDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateTextDataInterface;

class SourceParser implements SourceParserInterface
{
    public function parse(string $html, TemplateNodeInterface $node): array
    {
        return $this->makeRows(
            (new Crawler($html))->filter($node->getSelector()),
            $node,
        );
    }

    /**
     * @param Crawler               $html
     * @param TemplateNodeInterface $node
     * @return array<int, SourceRowInterface>
     */
    protected function makeRows(
        Crawler $html,
        TemplateNodeInterface $node,
    ): array {
        $rows = [];

        $html->each(function (Crawler $item) use (&$rows, $node): void {
            $row = new SourceRow();

            $this->addToRow($item, $node, $row);

            $rows[] = $row;
        });

        return $rows;
    }

    protected function makeGroup(
        Crawler $html,
        TemplateNodeInterface $node,
    ): SourceGroupInterface {
        /** @var string $groupName */
        $groupName  = $node->getGroupName();

        $group = new SourceGroup($groupName);

        $rows = $this->makeRows(
            $html->children($node->getSelector()),
            $node,
        );

        foreach ($rows as $row) {
            $group->addRow($row);
        }

        return $group;
    }

    protected function addToRow(
        Crawler $html,
        TemplateNodeInterface $node,
        SourceRowInterface $row,
    ): void {
        if ($node->hasData()) {
            $data = $this->getPlaceholderData($html, $node);

            foreach ($data as $placeholder => $value) {
                $row->addData($placeholder, $value);
            }
        }

        foreach ($node->getChildren() as $child) {
            if ($child->isGroup()) {
                $group = $this->makeGroup($html, $child);

                $row->addGroup($group);

                continue;
            }

            $html->children($child->getSelector())->each(function (Crawler $item) use ($child, $row): void {
                $this->addToRow($item, $child, $row);
            });
        }
    }

    /**
     * @param Crawler               $html
     * @param TemplateNodeInterface $node
     * @return array<string, string>
     */
    protected function getPlaceholderData(Crawler $html, TemplateNodeInterface $node): array
    {
        $data = [];

        foreach ($node->getData() as $item) {
            if ($item instanceof TemplateTextDataInterface) {
                $data[$item->getPlaceholder()] = $html->text();
            }

            if ($item instanceof TemplateAttributeDataInterface) {
                $data[$item->getPlaceholder()] = $this->getAttributeData($html, $item);
            }
        }

        return $data;
    }

    protected function getAttributeData(Crawler $html, TemplateAttributeDataInterface $data): string
    {
        /** @var string $attribute */
        $attribute = $html->attr(
            $data->getAttribute(),
        );

        return $attribute;
    }
}
