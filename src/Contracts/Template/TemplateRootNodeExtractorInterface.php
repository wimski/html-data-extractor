<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use DOMNode;
use Wimski\HtmlDataExtractor\Exceptions\TemplateValidationException;

interface TemplateRootNodeExtractorInterface
{
    /**
     * @param string $template
     * @return DOMNode
     * @throws TemplateValidationException
     */
    public function extract(string $template): DOMNode;
}
