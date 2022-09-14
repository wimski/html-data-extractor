<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Matching;

interface GroupMatcherInterface
{
    public function matchesGroupStart(string $value): bool;
    public function matchesGroupEnd(string $value): bool;
    public function getGroupStartMatch(string $value): ?PregMatchInterface;
}
