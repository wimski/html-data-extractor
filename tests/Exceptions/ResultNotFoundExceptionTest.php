<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\ResultNotFoundException;

class ResultNotFoundExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_placeholder(): void
    {
        $exception = new ResultNotFoundException('foo');

        self::assertSame('foo', $exception->getPlaceholder());
    }
}
