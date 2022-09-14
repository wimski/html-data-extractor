<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template\Data;

use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateDataInterface;

abstract class AbstractTemplateData implements TemplateDataInterface
{
    public function __construct(
        protected string $placeholder
    ) {
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }
}
