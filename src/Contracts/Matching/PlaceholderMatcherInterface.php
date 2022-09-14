<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Matching;

interface PlaceholderMatcherInterface
{
    public function matchesPlaceholder(string $value): bool;
    public function getPlaceholderMatch(string $value): ?PregMatchInterface;
}
