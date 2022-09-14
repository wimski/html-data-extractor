<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateDataInterface;

interface TemplateNodeInterface
{
    public function getSelector(): string;

    public function makeGroup(string $name): void;
    public function isGroup(): bool;
    public function getGroupName(): ?string;

    public function getParent(): ?TemplateNodeInterface;
    public function setParent(TemplateNodeInterface $parent): void;

    /**
     * @return array<int, TemplateNodeInterface>
     */
    public function getChildren(): array;
    public function addChild(TemplateNodeInterface $child): void;
    public function hasChildren(): bool;

    /**
     * @return array<int, TemplateDataInterface>
     */
    public function getData(): array;
    public function addData(TemplateDataInterface $data): void;
    public function hasData(): bool;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
