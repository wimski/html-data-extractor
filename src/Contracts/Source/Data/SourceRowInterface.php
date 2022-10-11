<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Source\Data;

use Wimski\HtmlDataExtractor\Exceptions\SourceRowDataDoesNotExistException;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupDoesNotExistException;

interface SourceRowInterface
{
    /**
     * @return array<int, SourceGroupInterface>
     */
    public function getGroups(): array;

    /**
     * @param string $name
     * @return SourceGroupInterface
     * @throws SourceRowGroupDoesNotExistException
     */
    public function getGroupByName(string $name): SourceGroupInterface;

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

    /**
     * @param string $placeholder
     * @return SourceDataInterface
     * @throws SourceRowDataDoesNotExistException
     */
    public function getDataByPlaceholder(string $placeholder): SourceDataInterface;

    /**
     * @param string $placeholder
     * @return string
     * @throws SourceRowDataDoesNotExistException
     */
    public function getFirstDataValueByPlaceholder(string $placeholder): string;

    public function addData(string $placeholder, string $value): void;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
