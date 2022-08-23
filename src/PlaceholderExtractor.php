<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor;

use Wimski\HtmlDataExtractor\Contracts\PlaceholderExtractorInterface;

class PlaceholderExtractor implements PlaceholderExtractorInterface
{
    public function extract(string $value): ?string
    {
        if (preg_match('/^\s*{{\s*(\S+)\s*}}\s*$/', $value, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
