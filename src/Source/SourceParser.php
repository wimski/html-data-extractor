<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Source;

use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceGroupInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\SourceParserInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateAttributeDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateTextDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Exceptions\SourceParsingException;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupAlreadyExistsException;
use Wimski\HtmlDataExtractor\Source\Data\SourceGroup;
use Wimski\HtmlDataExtractor\Source\Data\SourceRow;

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
     * @throws SourceParsingException
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

    /**
     * @param Crawler               $html
     * @param TemplateNodeInterface $node
     * @return SourceGroupInterface
     * @throws SourceParsingException
     */
    protected function makeGroup(
        Crawler $html,
        TemplateNodeInterface $node,
    ): SourceGroupInterface {
        /** @var string $groupName */
        $groupName = $node->getGroupName();

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

    /**
     * @param Crawler               $html
     * @param TemplateNodeInterface $node
     * @param SourceRowInterface    $row
     * @return void
     * @throws SourceParsingException
     */
    protected function addToRow(
        Crawler $html,
        TemplateNodeInterface $node,
        SourceRowInterface $row,
    ): void {
        $this->addData($html, $node, $row);

        foreach ($node->getChildren() as $child) {
            if ($child->isGroup()) {
                $group = $this->makeGroup($html, $child);

                try {
                    $row->addGroup($group);
                } catch (SourceRowGroupAlreadyExistsException $exception) {
                    throw new SourceParsingException($exception);
                }

                continue;
            }

            $html->children($child->getSelector())->each(function (Crawler $item) use ($child, $row): void {
                $this->addToRow($item, $child, $row);
            });
        }
    }

    protected function addData(
        Crawler $html,
        TemplateNodeInterface $node,
        SourceRowInterface $row,
    ): void {
        foreach ($node->getData() as $item) {
            if ($item instanceof TemplateTextDataInterface) {
                $row->addData($item->getPlaceholder(), $html->text());
            }

            if ($item instanceof TemplateAttributeDataInterface) {
                $row->addData($item->getPlaceholder(), $this->getAttributeData($html, $item));
            }
        }
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
