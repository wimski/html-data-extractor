<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Exceptions\TemplateParsingException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateValidationException;

interface TemplateParserInterface
{
    /**
     * @param string $template
     * @return TemplateNodeInterface
     * @throws TemplateValidationException
     * @throws TemplateParsingException
     */
    public function parse(string $template): TemplateNodeInterface;
}
