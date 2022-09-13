<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use Wimski\HtmlDataExtractor\Contracts\PregMatchInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\PlaceholderMatcherInterface;

class PlaceholderMatcher implements PlaceholderMatcherInterface
{
    use MatchesValues;

    public function matchesPlaceholder(string $value): bool
    {
        return $this->matches($this->getPlaceholderPattern(), $value);
    }

    public function getPlaceholderMatch(string $value): ?PregMatchInterface
    {
        $matches = $this->getMatches($this->getPlaceholderPattern(), $value);

        if (empty($matches)) {
            return null;
        }

        return $matches[0];
    }

    protected function getPlaceholderPattern(): string
    {
        // language=regexp
        return '/{{\s*(\S+)\s*}}/';
    }
}
