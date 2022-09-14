<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Source\Data;

use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupAlreadyExistsException;

interface SourceRowInterface
{
    /**
     * @return array<int, SourceGroupInterface>
     */
    public function getGroups(): array;

    /**
     * @param SourceGroupInterface $group
     * @return void
     * @throws SourceRowGroupAlreadyExistsException
     */
    public function addGroup(SourceGroupInterface $group): void;

    /**
     * @return array<int, SourceDataInterface>
     */
    public function getData(): array;
    public function addData(string $placeholder, string $value): void;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
