<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Matching;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Matching\PregMatch;

class PregMatchTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_properties(): void
    {
        $pregMatch = new PregMatch('foo', 'bar');

        self::assertSame('foo', $pregMatch->getTotalMatch());
        self::assertSame('bar', $pregMatch->getPartialMatch());
    }
}
