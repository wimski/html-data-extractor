<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;

class TemplateParsingException extends Exception
{
    public function __construct(Exception $previous)
    {
        parent::__construct('Parsing of the template failed', 0, $previous);
    }
}
