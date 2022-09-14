<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Matching;

use Wimski\HtmlDataExtractor\Contracts\Matching\PregMatchInterface;

class PregMatch implements PregMatchInterface
{
    public function __construct(
        protected string $totalMatch,
        protected string $partialMatch,
    ) {
    }

    public function getTotalMatch(): string
    {
        return $this->totalMatch;
    }

    public function getPartialMatch(): string
    {
        return $this->partialMatch;
    }
}
