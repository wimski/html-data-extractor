<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Extractors;

use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Exceptions\HtmlDataExtractionException;

interface HtmlDataExtractorInterface
{
    /**
     * @param string $source
     * @param string $template
     * @return array<int, SourceRowInterface>
     * @throws HtmlDataExtractionException
     */
    public function extract(string $source, string $template): array;
}
