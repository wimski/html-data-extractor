<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

use Symfony\Component\DomCrawler\Crawler;

interface SelectorFactoryInterface
{
    public const EXTRACT_TAG = 'extract';

    public function make(Crawler $html): string;
}
