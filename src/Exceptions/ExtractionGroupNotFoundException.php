<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;

class ExtractionGroupNotFoundException extends Exception
{
    public function __construct(
        protected string $name,
    ) {
        parent::__construct();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
