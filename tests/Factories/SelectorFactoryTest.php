<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Factories;

use DOMNode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\Factories\SelectorFactory;
use Wimski\HtmlDataExtractor\Matching\PlaceholderMatcher;

class SelectorFactoryTest extends TestCase
{
    protected SelectorFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new SelectorFactory(
            new PlaceholderMatcher(),
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

        self::assertSame("div.container.wide[data-placeholder][data-that][data-this='foo']#main", $selector);
    }
}
