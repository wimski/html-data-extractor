<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use Wimski\HtmlDataExtractor\Contracts\Template\TemplateDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;

class TemplateNode implements TemplateNodeInterface
{
    protected ?string $groupName = null;
    protected ?TemplateNodeInterface $parent = null;

    /**
     * @var array<int, TemplateNodeInterface>
     */
    protected array $children = [];

    /**
     * @var array<int, TemplateDataInterface>
     */
    protected array $data = [];

    public function __construct(
        protected string $selector,
    ) {
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function makeGroup(string $name): void
    {
        $this->groupName = $name;
    }

    public function isGroup(): bool
    {
        return $this->groupName !== null;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getParent(): ?TemplateNodeInterface
    {
        return $this->parent;
    }

    public function setParent(TemplateNodeInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(TemplateNodeInterface $child): void
    {
        $this->children[] = $child;
    }

    public function hasChildren(): bool
    {
        return ! empty($this->children);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function addData(TemplateDataInterface $data): void
    {
        $this->data[] = $data;
    }

    public function hasData(): bool
    {
        return ! empty($this->data);
    }

    public function toArray(): array
    {
        return [
            'selector' => $this->selector,
            'group'    => $this->groupName,
            'nodes'    => array_map(function (TemplateNodeInterface $node): array {
                return $node->toArray();
            }, $this->children),
            'data' => array_map(function (TemplateDataInterface $data): string {
                return $data->getPlaceholder();
            }, $this->data),
        ];
    }
}
