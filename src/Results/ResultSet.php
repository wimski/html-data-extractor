<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Results;

use Wimski\HtmlDataExtractor\Contracts\Results\ResultInterface;
use Wimski\HtmlDataExtractor\Contracts\Results\ResultSetInterface;
use Wimski\HtmlDataExtractor\Exceptions\ResultNotFoundException;

class ResultSet implements ResultSetInterface
{
    /**
     * @var array<int, ResultInterface>
     */
    protected array $results = [];

    public function getResult(string $placeholder): ResultInterface
    {
        foreach ($this->results as $result) {
            if ($result->getPlaceholder() === $placeholder) {
                return $result;
            }
        }

        throw new ResultNotFoundException($placeholder);
    }

    public function addResult(ResultInterface $result): void
    {
        $this->results[] = $result;
    }

    public function getPlaceholders(): array
    {
        return array_map(function (ResultInterface $result): string {
            return $result->getPlaceholder();
        }, $this->results);
    }
}
