<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Contracts\PregMatchInterface;

interface PlaceholderMatcherInterface
{
    public function matchesPlaceholder(string $value): bool;
    public function getPlaceholderMatch(string $value): ?PregMatchInterface;
}
