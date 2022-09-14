<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Source;

use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Exceptions\SourceParsingException;

interface SourceParserInterface
{
    /**
     * @param string                $html
     * @param TemplateNodeInterface $node
     * @return array<int, SourceRowInterface>
     * @throws SourceParsingException
     */
    public function parse(string $html, TemplateNodeInterface $node): array;
}
