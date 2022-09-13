<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\ExtractionGroupNotFoundException;

class ExtractionGroupNotFoundExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_name(): void
    {
        $exception = new ExtractionGroupNotFoundException('foo');

        self::assertSame('foo', $exception->getName());
    }
}
