<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Matching;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Matching\PlaceholderMatcher;

class PlaceholderMatcherTest extends TestCase
{
    protected PlaceholderMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->matcher = new PlaceholderMatcher();
    }

    /**
     * @test
     * @dataProvider placeholderDataProvider
     */
    public function it_matches_placeholders(string $value, ?string $result, bool $willMatch): void
    {
        static::assertSame($willMatch, $this->matcher->matchesPlaceholder($value));
    }

    /**
     * @test
     * @dataProvider placeholderDataProvider
     */
    public function it_returns_placeholder_matches(string $value, ?string $result, bool $willMatch): void
    {
        $match = $this->matcher->getPlaceholderMatch($value);

        static::assertSame($result, $match?->getPartialMatch());
    }

    /**
     * @return array<int, array<int, bool|string|null>>
     */
    public function placeholderDataProvider(): array
    {
        return [
            ['{{foo}}', 'foo', true],
            ['{{ foo }}', 'foo', true],
            ['{{foo  }}', 'foo', true],
            ['{{  foo}}', 'foo', true],
            ['  {{foo}}  ', 'foo', true],
            ['{{ foo }} {{ bar }}', 'foo', true],
            ['{foo}', null, false],
            ['foo', null, false],
        ];
    }
}
