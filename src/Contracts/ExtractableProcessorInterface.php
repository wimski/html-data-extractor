<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

use Wimski\HtmlDataExtractor\Contracts\Results\ResultSetInterface;

interface ExtractableProcessorInterface
{
    /**
     * @param string                           $html
     * @param array<int, ExtractableInterface> $extractables
     * @return ResultSetInterface
     */
    public function process(string $html, array $extractables): ResultSetInterface;
}
