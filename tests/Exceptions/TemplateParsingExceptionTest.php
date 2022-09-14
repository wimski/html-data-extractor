<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use Exception;
use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\TemplateParsingException;

class TemplateParsingExceptionTest extends TestCase
{
    protected TemplateParsingException $exception;
    protected Exception $previous;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previous  = $this->createMock(Exception::class);
        $this->exception = new TemplateParsingException(
            $this->previous,
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('Parsing of the template failed', $this->exception->getMessage());
    }

    /**
     * @test
     */
    public function it_returns_a_previous_exception(): void
    {
        self::assertSame($this->previous, $this->exception->getPrevious());
    }
}
