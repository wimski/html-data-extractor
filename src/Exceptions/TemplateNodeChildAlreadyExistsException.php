<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Exceptions;

use Exception;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;

class TemplateNodeChildAlreadyExistsException extends Exception
{
    public function __construct(
        protected TemplateNodeInterface $parent,
        protected TemplateNodeInterface $child,
    ) {
        parent::__construct('The template node already has a similar child');
    }

    public function getParent(): TemplateNodeInterface
    {
        return $this->parent;
    }

    public function getChild(): TemplateNodeInterface
    {
        return $this->child;
    }
}
