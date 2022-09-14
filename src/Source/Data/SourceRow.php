<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Source\Data;

use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceGroupInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Exceptions\ExtractionDataNotFoundException;
use Wimski\HtmlDataExtractor\Exceptions\ExtractionGroupNotFoundException;
use Wimski\HtmlDataExtractor\Exceptions\FirstValueNotFoundException;

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

    public function addGroup(SourceGroupInterface $group): void
    {
        try {
            $this->getGroupByName($group->getName());
        } catch (ExtractionGroupNotFoundException $exception) {
            $this->groups[] = $group;
        }
    }

    public function getGroupByName(string $name): SourceGroupInterface
    {
        foreach ($this->groups as $group) {
            if ($group->getName() === $name) {
                return $group;
            }
        }

        throw new ExtractionGroupNotFoundException($name);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function addData(string $placeholder, string $value): SourceDataInterface
    {
        try {
            $data = $this->getDataByPlaceholder($placeholder);
        } catch (ExtractionDataNotFoundException $exception) {
            $data = new SourceData($placeholder);

            $this->data[] = $data;
        }

        $data->addValue($value);

        return $data;
    }

    public function getDataByPlaceholder(string $placeholder): SourceDataInterface
    {
        foreach ($this->data as $item) {
            if ($item->getPlaceholder() === $placeholder) {
                return $item;
            }
        }

        throw new ExtractionDataNotFoundException($placeholder);
    }

    public function getFirstValueByPlaceholder(string $placeholder): string
    {
        $data = $this->getDataByPlaceholder($placeholder);

        $values = $data->getValues();

        if (empty($values)) {
            throw new FirstValueNotFoundException($placeholder);
        }

        return $values[0];
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
