<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Factories;

use DOMNode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\Extractors\PlaceholderNameExtractor;
use Wimski\HtmlDataExtractor\Factories\SelectorFactory;

class SelectorFactoryTest extends TestCase
{
    protected SelectorFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new SelectorFactory(
            new PlaceholderNameExtractor(),
        );
    }

    /**
     * @test
     */
    public function it_makes_a_selector(): void
    {
        /** @var DOMNode $node */
        $node = (new Crawler('
            <div id="main" class="container wide" data-this="foo" data-that data-placeholder="{{ bar }}"></div>
        '))->filter('#main')->getNode(0);

        $selector = $this->factory->make($node);

        self::assertSame("div#main.container.wide[data-this='foo'][data-that][data-placeholder]", $selector);
    }
}
