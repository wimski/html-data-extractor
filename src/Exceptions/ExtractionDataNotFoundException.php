<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;

class ExtractionDataNotFoundException extends Exception
{
    public function __construct(
        protected string $placeholder,
    ) {
        parent::__construct();
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }
}
