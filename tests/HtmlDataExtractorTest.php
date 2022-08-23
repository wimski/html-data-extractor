<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\ExtractableFinder;
use Wimski\HtmlDataExtractor\ExtractableProcessor;
use Wimski\HtmlDataExtractor\HtmlDataExtractor;
use Wimski\HtmlDataExtractor\PlaceholderExtractor;
use Wimski\HtmlDataExtractor\SelectorFactory;

class HtmlDataExtractorTest extends TestCase
{
    protected HtmlDataExtractor $htmlDataExtractor;

    protected function setUp(): void
    {
        parent::setUp();

        $placeholderExtractor = new PlaceholderExtractor();
        $selectorFactory      = new SelectorFactory($placeholderExtractor);
        $extractableFinder    = new ExtractableFinder($placeholderExtractor, $selectorFactory);
        $extractableProcessor = new ExtractableProcessor();

        $this->htmlDataExtractor = new HtmlDataExtractor(
            $extractableFinder,
            $extractableProcessor,
        );
    }

    /**
     * @test
     */
    public function it_extracts_html_data(): void
    {
        /** @var string $source */
        $source = file_get_contents(__DIR__ . '/stubs/source.html');

        /** @var string $template */
        $template = file_get_contents(__DIR__ . '/stubs/template.html');

        $resultSet = $this->htmlDataExtractor->extract($source, $template);

        self::assertSame(['text', 'attr', 'notfound', 'item'], $resultSet->getPlaceholders());

        $textResult = $resultSet->getResult('text');
        self::assertSame(['Ipsum'], $textResult->getData());

        $attrResult = $resultSet->getResult('attr');
        self::assertSame(['bar'], $attrResult->getData());

        $notfoundResult = $resultSet->getResult('notfound');
        self::assertEmpty($notfoundResult->getData());

        $itemResult = $resultSet->getResult('item');
        self::assertSame(['second', 'third'], $itemResult->getData());
    }
}
