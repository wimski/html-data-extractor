<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeChildAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeDataAlreadyExistsException;

class TemplateNode implements TemplateNodeInterface
{
    protected ?string $groupName = null;

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

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(TemplateNodeInterface $child): void
    {
        if ($this->hasChild($child)) {
            throw new TemplateNodeChildAlreadyExistsException($this, $child);
        }

        $this->children[] = $child;
    }

    protected function hasChild(TemplateNodeInterface $node): bool
    {
        foreach ($this->children as $child) {
            if ($node->getSelector() === $child->getSelector()) {
                return true;
            }
        }

        return false;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function addData(TemplateDataInterface $data): void
    {
        if ($this->hasData($data)) {
            throw new TemplateNodeDataAlreadyExistsException($this, $data);
        }

        $this->data[] = $data;
    }

    protected function hasData(TemplateDataInterface $data): bool
    {
        foreach ($this->data as $item) {
            if ($data->getPlaceholder() === $item->getPlaceholder()) {
                return true;
            }
        }

        return false;
    }

    public function toArray(): array
    {
        return [
            'selector' => $this->selector,
            'group'    => $this->groupName,
            'nodes'    => array_map(function (TemplateNodeInterface $node): array {
                return $node->toArray();
            }, $this->children),
            'data'     => array_map(function (TemplateDataInterface $data): string {
                return $data->getPlaceholder();
            }, $this->data),
        ];
    }
}
