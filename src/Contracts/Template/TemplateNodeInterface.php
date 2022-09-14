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

    /**
     * @return array<int, TemplateNodeInterface>
     */
    public function getChildren(): array;
    public function addChild(TemplateNodeInterface $child): void;

    /**
     * @return array<int, TemplateDataInterface>
     */
    public function getData(): array;
    public function addData(TemplateDataInterface $data): void;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
