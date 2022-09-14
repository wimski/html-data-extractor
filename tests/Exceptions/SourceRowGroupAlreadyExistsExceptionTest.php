<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceGroupInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupAlreadyExistsException;

class SourceRowGroupAlreadyExistsExceptionTest extends TestCase
{
    protected SourceRowGroupAlreadyExistsException $exception;
    protected SourceRowInterface $row;
    protected SourceGroupInterface $group;

    protected function setUp(): void
    {
        parent::setUp();

        $this->row       = $this->createMock(SourceRowInterface::class);
        $this->group     = $this->createMock(SourceGroupInterface::class);
        $this->exception = new SourceRowGroupAlreadyExistsException(
            $this->row,
            $this->group,
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('The source row already has a similar group', $this->exception->getMessage());
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
    public function it_returns_a_group(): void
    {
        self::assertSame($this->group, $this->exception->getGroup());
    }
}
