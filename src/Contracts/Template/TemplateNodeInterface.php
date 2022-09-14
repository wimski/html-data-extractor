<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateDataInterface;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeChildAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeDataAlreadyExistsException;

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

    /**
     * @param TemplateNodeInterface $child
     * @return void
     * @throws TemplateNodeChildAlreadyExistsException
     */
    public function addChild(TemplateNodeInterface $child): void;

    /**
     * @return array<int, TemplateDataInterface>
     */
    public function getData(): array;

    /**
     * @param TemplateDataInterface $data
     * @return void
     * @throws TemplateNodeDataAlreadyExistsException
     */
    public function addData(TemplateDataInterface $data): void;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
