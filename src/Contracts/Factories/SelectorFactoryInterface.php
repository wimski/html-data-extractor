<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Factories;

use DOMNode;

interface SelectorFactoryInterface
{
    public function make(DOMNode $node): string;
}
