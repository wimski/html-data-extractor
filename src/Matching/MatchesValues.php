<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Matching;

use Wimski\HtmlDataExtractor\Contracts\Matching\PregMatchInterface;

trait MatchesValues
{
    protected function matches(string $pattern, string $value): bool
    {
        return preg_match($pattern, $value) === 1;
    }

    /**
     * @param string $pattern
     * @param string $value
     * @return array<int, PregMatchInterface>
     */
    protected function getMatches(string $pattern, string $value): array
    {
        if (preg_match_all($pattern, $value, $matches, PREG_SET_ORDER) >= 1) {
            return $this->transformMatches($matches);
        }

        return [];
    }

    /**
     * @param array<int, array<int, string>> $matches
     * @return array<int, PregMatchInterface>
     */
    private function transformMatches(array $matches): array
    {
        return array_map(function (array $match): PregMatchInterface {
            return new PregMatch(
                $match[0],
                $match[1],
            );
        }, $matches);
    }
}
