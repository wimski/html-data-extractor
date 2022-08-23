<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

interface ExtractableFinderInterface
{
    /**
     * @param string $html
     * @return array<int, ExtractableInterface>
     */
    public function find(string $html): array;
}
