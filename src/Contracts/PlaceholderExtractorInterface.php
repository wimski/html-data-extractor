<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

interface PlaceholderExtractorInterface
{
    public function extract(string $value): ?string;
}
