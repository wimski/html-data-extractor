<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupDoesNotExistException;

class SourceRowGroupDoesNotExistExceptionTest extends TestCase
{
    protected SourceRowGroupDoesNotExistException $exception;
    protected SourceRowInterface $row;

    protected function setUp(): void
    {
        parent::setUp();

        $this->row       = $this->createMock(SourceRowInterface::class);
        $this->exception = new SourceRowGroupDoesNotExistException(
            $this->row,
            'foobar',
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('The source row does not contain a group with the requested name', $this->exception->getMessage());
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
    public function it_returns_a_group_name(): void
    {
        self::assertSame('foobar', $this->exception->getGroupName());
    }
}
