<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use DOMNode;

interface TemplateDataExtractorInterface
{
    /**
     * @param DOMNode $node
     * @return array<int, TemplateDataInterface>
     */
    public function extract(DOMNode $node): array;
}
