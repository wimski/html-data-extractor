<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Source;

use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;

interface SourceParserInterface
{
    /**
     * @param string                $html
     * @param TemplateNodeInterface $node
     * @return array<int, SourceRowInterface>
     */
    public function parse(string $html, TemplateNodeInterface $node): array;
}
