<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts;

use Wimski\HtmlDataExtractor\Enums\ExtractableTypeEnum;

interface ExtractableInterface
{
    public function getType(): ExtractableTypeEnum;
    public function getPlaceholder(): string;
    public function getSelector(): string;
    public function getAttributeName(): ?string;
}
