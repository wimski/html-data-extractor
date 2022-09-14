<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;

class SourceParsingException extends Exception
{
    public function __construct(Exception $previous)
    {
        parent::__construct('Parsing of the source failed', 0, $previous);
    }
}
