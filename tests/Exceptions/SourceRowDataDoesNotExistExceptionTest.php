<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowDataDoesNotExistException;

class SourceRowDataDoesNotExistExceptionTest extends TestCase
{
    protected SourceRowDataDoesNotExistException $exception;
    protected SourceRowInterface $row;

    protected function setUp(): void
    {
        parent::setUp();

        $this->row       = $this->createMock(SourceRowInterface::class);
        $this->exception = new SourceRowDataDoesNotExistException(
            $this->row,
            'foobar',
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('The source row does not contain data with the requested placeholder', $this->exception->getMessage());
    }

    /**
     * @test
     */
    public function it_returns_a_row(): void
    {
        self::assertSame($this->row, $this->exception->getRow());
    }

    /**
     * @test
     */
    public function it_returns_a_placeholder(): void
    {
        self::assertSame('foobar', $this->exception->getPlaceholder());
    }
}
