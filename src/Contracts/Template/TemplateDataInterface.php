<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Template;

interface TemplateDataInterface
{
    public function getPlaceholder(): string;
}
