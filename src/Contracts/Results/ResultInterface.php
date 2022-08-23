<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Results;

interface ResultInterface
{
    public function getPlaceholder(): string;

    /**
     * @return array<int, string>
     */
    public function getData(): array;
}
