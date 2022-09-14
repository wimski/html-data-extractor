<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Source\Data;

interface SourceDataInterface
{
    public function getPlaceholder(): string;

    /**
     * @return array<int, string>
     */
    public function getValues(): array;
    public function addValue(string $value): void;
}
