<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;
use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;

class TemplateNodeDataAlreadyExistsException extends Exception
{
    public function __construct(
        protected TemplateNodeInterface $node,
        protected TemplateDataInterface $data,
    ) {
        parent::__construct('The template node already has similar data');
    }

    public function getNode(): TemplateNodeInterface
    {
        return $this->node;
    }

    public function getData(): TemplateDataInterface
    {
        return $this->data;
    }
}
