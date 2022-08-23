<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\PlaceholderExtractor;

class PlaceholderExtractorTest extends TestCase
{
    protected PlaceholderExtractor $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = new PlaceholderExtractor();
    }

    /**
     * @test
     * @dataProvider placeholderDataProvider
     */
    public function it_extracts_placeholders(string $value, ?string $result): void
    {
        self::assertSame($result, $this->extractor->extract($value));
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    public function placeholderDataProvider(): array
    {
        return [
            ['{{foo}}', 'foo'],
            ['{{ foo }}', 'foo'],
            ['{{foo  }}', 'foo'],
            ['{{  foo}}', 'foo'],
            ['  {{foo}}  ', 'foo'],
            ['{{ foo }} {{ bar }}', null],
            ['{foo}', null],
            ['foo', null],
        ];
    }
}
