<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;

class HtmlDataExtractionException extends Exception
{
    public function __construct(Exception $previous)
    {
        parent::__construct('', 0, $previous);
    }
}
