<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;

class SourceRowGroupDoesNotExistException extends Exception
{
    public function __construct(
        protected SourceRowInterface $row,
        protected string $groupName,
    ) {
        parent::__construct('The source row does not contain a group with the requested name');
    }

    public function getRow(): SourceRowInterface
    {
        return $this->row;
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }
}
