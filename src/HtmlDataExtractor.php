<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor;

use Wimski\HtmlDataExtractor\Contracts\ExtractableFinderInterface;
use Wimski\HtmlDataExtractor\Contracts\ExtractableProcessorInterface;
use Wimski\HtmlDataExtractor\Contracts\HtmlDataExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\Results\ResultSetInterface;

class HtmlDataExtractor implements HtmlDataExtractorInterface
{
    public function __construct(
        protected ExtractableFinderInterface $extractableFinder,
        protected ExtractableProcessorInterface $extractableProcessor,
    ) {
    }

    public function extract(string $source, string $template): ResultSetInterface
    {
        $extractables = $this->extractableFinder->find($template);

        return $this->extractableProcessor->process($source, $extractables);
    }
}
