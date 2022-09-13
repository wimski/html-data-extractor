<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Source;

use Wimski\HtmlDataExtractor\Contracts\Source\SourceDataInterface;

class SourceData implements SourceDataInterface
{
    /**
     * @var array<int, string>
     */
    protected array $values = [];

    public function __construct(
        protected string $placeholder,
    ) {
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function addValue(string $value): void
    {
        $this->values[] = $value;
    }
}
