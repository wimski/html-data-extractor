<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Source\Data;

use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceGroupInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowDataDoesNotExistException;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupDoesNotExistException;

class SourceRow implements SourceRowInterface
{
    /**
     * @var array<int, SourceGroupInterface>
     */
    protected array $groups = [];

    /**
     * @var array<int, SourceDataInterface>
     */
    protected array $data = [];

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getGroupByName(string $name): SourceGroupInterface
    {
        foreach ($this->groups as $group) {
            if ($group->getName() === $name) {
                return $group;
            }
        }

        throw new SourceRowGroupDoesNotExistException($this, $name);
    }

    public function addGroup(SourceGroupInterface $group): void
    {
        if ($this->hasGroup($group)) {
            throw new SourceRowGroupAlreadyExistsException($this, $group);
        }

        $this->groups[] = $group;
    }

    protected function hasGroup(SourceGroupInterface $group): bool
    {
        foreach ($this->groups as $sourceGroup) {
            if ($group->getName() === $sourceGroup->getName()) {
                return true;
            }
        }

        return false;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getFirstDataValueByPlaceholder(string $placeholder): string
    {
        foreach ($this->data as $item) {
            if ($item->getPlaceholder() === $placeholder) {
                return $item->getValues()[0];
            }
        }

        throw new SourceRowDataDoesNotExistException($this, $placeholder);
    }

    public function addData(string $placeholder, string $value): void
    {
        $data = null;

        foreach ($this->data as $item) {
            if ($item->getPlaceholder() === $placeholder) {
                $data = $item;

                break;
            }
        }

        if (! $data) {
            $data = new SourceData($placeholder);

            $this->data[] = $data;
        }

        $data->addValue($value);
    }

    public function toArray(): array
    {
        $arr = [];

        foreach ($this->data as $item) {
            $arr[$item->getPlaceholder()] = $item->getValues();
        }

        foreach ($this->groups as $group) {
            $arr[$group->getName()] = array_map(function (SourceRowInterface $row): array {
                return $row->toArray();
            }, $group->getRows());
        }

        return $arr;
    }
}
