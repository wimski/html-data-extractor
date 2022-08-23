<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor;

use Wimski\HtmlDataExtractor\Contracts\ExtractableInterface;
use Wimski\HtmlDataExtractor\Enums\ExtractableTypeEnum;

class Extractable implements ExtractableInterface
{
    public function __construct(
        protected ExtractableTypeEnum $type,
        protected string $placeholder,
        protected string $selector,
        protected ?string $attributeName = null,
    ) {
    }

    public function getType(): ExtractableTypeEnum
    {
        return $this->type;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }
}
