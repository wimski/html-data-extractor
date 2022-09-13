<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Source;

use Wimski\HtmlDataExtractor\Exceptions\ExtractionDataNotFoundException;
use Wimski\HtmlDataExtractor\Exceptions\ExtractionGroupNotFoundException;
use Wimski\HtmlDataExtractor\Exceptions\FirstValueNotFoundException;

interface SourceRowInterface
{
    /**
     * @return array<int, SourceGroupInterface>
     */
    public function getGroups(): array;
    public function hasGroups(): bool;
    public function addGroup(SourceGroupInterface $group): void;

    /**
     * @param string $name
     * @return SourceGroupInterface
     * @throws ExtractionGroupNotFoundException
     */
    public function getGroupByName(string $name): SourceGroupInterface;

    /**
     * @return array<int, SourceDataInterface>
     */
    public function getData(): array;
    public function hasData(): bool;
    public function addData(string $placeholder, string $value): SourceDataInterface;

    /**
     * @param string $placeholder
     * @return SourceDataInterface
     * @throws ExtractionDataNotFoundException
     */
    public function getDataByPlaceholder(string $placeholder): SourceDataInterface;

    /**
     * @param string $placeholder
     * @return string
     * @throws ExtractionDataNotFoundException
     * @throws FirstValueNotFoundException
     */
    public function getFirstValueByPlaceholder(string $placeholder): string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
