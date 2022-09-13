<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

use DOMDocument;

interface HtmlLoaderInterface
{
    public function load(string $html): DOMDocument;
}
