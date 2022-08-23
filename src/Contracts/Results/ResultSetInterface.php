<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Results;

use Wimski\HtmlDataExtractor\Exceptions\ResultNotFoundException;

interface ResultSetInterface
{
    /**
     * @param string $placeholder
     * @return ResultInterface
     * @throws ResultNotFoundException
     */
    public function getResult(string $placeholder): ResultInterface;

    /**
     * @return array<int, string>
     */
    public function getPlaceholders(): array;
}
