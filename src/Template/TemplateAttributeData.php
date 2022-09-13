<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use Wimski\HtmlDataExtractor\Contracts\Template\TemplateAttributeDataInterface;

class TemplateAttributeData extends AbstractTemplateData implements TemplateAttributeDataInterface
{
    public function __construct(
        string $placeholder,
        protected string $attribute,
    ) {
        parent::__construct($placeholder);
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }
}
