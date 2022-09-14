<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceGroupInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;

class SourceRowGroupAlreadyExistsException extends Exception
{
    public function __construct(
        protected SourceRowInterface $row,
        protected SourceGroupInterface $group,
    ) {
        parent::__construct('The source row already has a similar group');
    }

    public function getRow(): SourceRowInterface
    {
        return $this->row;
    }

    public function getGroup(): SourceGroupInterface
    {
        return $this->group;
    }
}
