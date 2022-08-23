<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\PlaceholderExtractor;
use Wimski\HtmlDataExtractor\SelectorFactory;

class SelectorFactoryTest extends TestCase
{
    protected SelectorFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new SelectorFactory(
            new PlaceholderExtractor(),
        );
    }

    /**
     * @test
     */
    public function it_makes_a_selector(): void
    {
        $html = (new Crawler('
            <section id="main" class="container wide" data-foo="bar">
                <p data-bar="{{ bar }}">
                    <span>{{ value }}</span>
                </p>
            </section>
        '))->filter('span');

        $selector = $this->factory->make($html);

        self::assertSame("html > body > section#main.container.wide[data-foo='bar'] > p[data-bar] > span", $selector);
    }
}
