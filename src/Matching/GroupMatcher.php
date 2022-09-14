<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Matching;

use Wimski\HtmlDataExtractor\Contracts\Matching\GroupMatcherInterface;
use Wimski\HtmlDataExtractor\Contracts\Matching\PregMatchInterface;

class GroupMatcher implements GroupMatcherInterface
{
    use MatchesValues;

    public function matchesGroupStart(string $value): bool
    {
        return $this->matches($this->getGroupStartPattern(), $value);
    }

    public function matchesGroupEnd(string $value): bool
    {
        return $this->matches($this->getGroupEndPattern(), $value);
    }

    public function getGroupStartMatch(string $value): ?PregMatchInterface
    {
        $matches = $this->getMatches($this->getGroupStartPattern(), $value);

        if (empty($matches)) {
            return null;
        }

        return $matches[0];
    }

    protected function getGroupStartPattern(): string
    {
        // language=regexp
        return '/{%\s*group\s(\S+)\s*%}/';
    }

    protected function getGroupEndPattern(): string
    {
        // language=regexp
        return '/{%\s*endgroup\s*%}/';
    }
}
