<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Exceptions\TemplateValidationException;

interface TemplateParserInterface
{
    /**
     * @param string $template
     * @return TemplateNodeInterface
     * @throws TemplateValidationException
     */
    public function parse(string $template): TemplateNodeInterface;
}
