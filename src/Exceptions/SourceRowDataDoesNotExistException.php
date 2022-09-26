<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;

class SourceRowDataDoesNotExistException extends Exception
{
    public function __construct(
        protected SourceRowInterface $row,
        protected string $placeholder,
    ) {
        parent::__construct('The source row does not contain data with the requested placeholder');
    }

    public function getRow(): SourceRowInterface
    {
        return $this->row;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }
}
