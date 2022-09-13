<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor;

use DOMDocument;
use Wimski\HtmlDataExtractor\Contracts\HtmlLoaderInterface;

class HtmlLoader implements HtmlLoaderInterface
{
    public function load(string $html): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'utf-8');

        $dom->loadHTML(
            $html,
            LIBXML_HTML_NODEFDTD|LIBXML_HTML_NOIMPLIED|LIBXML_NOERROR,
        );

        return $dom;
    }
}
