<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use Exception;
use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\HtmlDataExtractionException;

class HtmlDataExtractionExceptionTest extends TestCase
{
    protected HtmlDataExtractionException $exception;
    protected Exception $previous;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previous  = $this->createMock(Exception::class);
        $this->exception = new HtmlDataExtractionException(
            $this->previous,
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('Extracting HTML data failed', $this->exception->getMessage());
    }

    /**
     * @test
     */
    public function it_returns_a_previous_exception(): void
    {
        self::assertSame($this->previous, $this->exception->getPrevious());
    }
}
