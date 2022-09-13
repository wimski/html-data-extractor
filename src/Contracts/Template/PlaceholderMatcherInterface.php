<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Contracts\PregMatchInterface;

interface PlaceholderMatcherInterface
{
    public function matchesPlaceholder(string $value): bool;

    /**
     * @param string $value
     * @return array<int, PregMatchInterface>
     */
    public function getPlaceholderMatch(string $value): array;
}
