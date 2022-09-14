<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\HtmlLoaderInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateRootNodeExtractorInterface;
use Wimski\HtmlDataExtractor\Exceptions\TemplateValidationException;

class TemplateRootNodeExtractor implements TemplateRootNodeExtractorInterface
{
    public function __construct(
        protected HtmlLoaderInterface $htmlLoader,
    ) {
    }

    public function extract(string $template): DOMNode
    {
        $html = $this->htmlLoader->load($template);

        $rootNode = $html->firstChild;

        if (! $rootNode || $rootNode->nodeType !== XML_ELEMENT_NODE) {
            throw new TemplateValidationException('The template is missing a single valid element root node');
        }

        return $rootNode;
    }
}
