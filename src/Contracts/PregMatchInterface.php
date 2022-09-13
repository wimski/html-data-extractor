<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

interface PregMatchInterface
{
    public function getTotalMatch(): string;
    public function getPartialMatch(): string;
}
