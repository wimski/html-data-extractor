<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\FirstValueNotFoundException;

class FirstValueNotFoundExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_placeholder(): void
    {
        $exception = new FirstValueNotFoundException('foo');

        self::assertSame('foo', $exception->getPlaceholder());
    }
}
