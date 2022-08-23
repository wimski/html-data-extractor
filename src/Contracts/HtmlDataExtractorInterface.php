<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

use Wimski\HtmlDataExtractor\Contracts\Results\ResultSetInterface;

interface HtmlDataExtractorInterface
{
    public function extract(string $source, string $template): ResultSetInterface;
}
