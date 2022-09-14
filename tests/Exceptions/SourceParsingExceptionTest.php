<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use Exception;
use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\SourceParsingException;

class SourceParsingExceptionTest extends TestCase
{
    protected SourceParsingException $exception;
    protected Exception $previous;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previous  = $this->createMock(Exception::class);
        $this->exception = new SourceParsingException(
            $this->previous,
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('Parsing of the source failed', $this->exception->getMessage());
    }

    /**
     * @test
     */
    public function it_returns_a_previous_exception(): void
    {
        self::assertSame($this->previous, $this->exception->getPrevious());
    }
}
