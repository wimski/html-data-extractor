<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Template;

use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Contracts\Matching\PlaceholderMatcherInterface;
use Wimski\HtmlDataExtractor\Template\TemplateDataExtractor;

class TemplateDataExtractorTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_an_empty_array_if_the_dom_node_is_not_an_element_node(): void
    {
        $extractor = new TemplateDataExtractor(
            $this->createMock(PlaceholderMatcherInterface::class),
        );

        $dom = new DOMDocument();
        $dom->loadHTML('<p>foo</p>', LIBXML_HTML_NODEFDTD|LIBXML_HTML_NOIMPLIED|LIBXML_NOERROR);

        /** @var DOMNode $paragraph */
        $paragraph = $dom->firstChild;

        /** @var DOMNode $text */
        $text = $paragraph->firstChild;

        self::assertSame([], $extractor->extract($text));
    }
}
