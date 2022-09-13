<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

use Wimski\HtmlDataExtractor\Exceptions\TemplateValidationException;

interface TemplateValidatorInterface
{
    /**
     * @param string $template
     * @return void
     * @throws TemplateValidationException
     */
    public function validate(string $template): void;
}
