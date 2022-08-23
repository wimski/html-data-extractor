<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Results;

use Wimski\HtmlDataExtractor\Contracts\Results\ResultInterface;

class Result implements ResultInterface
{
    /**
     * @var array<int, string>
     */
    protected array $data = [];

    public function __construct(
        protected string $placeholder,
    ) {
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function addData(string $value): void
    {
        $this->data[] = $value;
    }
}
