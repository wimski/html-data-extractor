<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Contracts\PregMatchInterface;

interface GroupMatcherInterface
{
    public function matchesGroupStart(string $value): bool;
    public function matchesGroupEnd(string $value): bool;

    /**
     * @param string $value
     * @return array<int, PregMatchInterface>
     */
    public function getGroupStartMatches(string $value): array;
}
