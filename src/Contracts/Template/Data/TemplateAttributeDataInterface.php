<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template\Data;

interface TemplateAttributeDataInterface extends TemplateDataInterface
{
    public function getAttribute(): string;
}
