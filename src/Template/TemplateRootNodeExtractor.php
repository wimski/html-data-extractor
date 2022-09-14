<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use DOMNode;
use Wimski\HtmlDataExtractor\Contracts\HtmlLoaderInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateRootNodeExtractorInterface;

class TemplateRootNodeExtractor implements TemplateRootNodeExtractorInterface
{
    public function __construct(
        protected HtmlLoaderInterface $htmlLoader,
    ) {
    }

    public function extract(string $template): DOMNode
    {
        $html = $this->htmlLoader->load($template);

        /** @var DOMNode $rootNode */
        $rootNode = $html->firstChild;

        return $rootNode;
    }
}
