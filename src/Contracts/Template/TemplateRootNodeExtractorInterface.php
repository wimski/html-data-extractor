<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use DOMNode;

interface TemplateRootNodeExtractorInterface
{
    public function extract(string $template): DOMNode;
}
