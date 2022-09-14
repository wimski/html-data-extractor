<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Extractors;

use Exception;
use Wimski\HtmlDataExtractor\Contracts\Extractors\HtmlDataExtractorInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\SourceParserInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateParserInterface;
use Wimski\HtmlDataExtractor\Exceptions\HtmlDataExtractionException;

class HtmlDataExtractor implements HtmlDataExtractorInterface
{
    public function __construct(
        protected TemplateParserInterface $templateParser,
        protected SourceParserInterface $sourceParser,
    ) {
    }

    public function extract(string $source, string $template): array
    {
        try {
            $templateNode = $this->templateParser->parse($template);

            return $this->sourceParser->parse($source, $templateNode);
        } catch (Exception $exception) {
            throw new HtmlDataExtractionException($exception);
        }
    }
}
