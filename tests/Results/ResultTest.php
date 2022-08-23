<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Results;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Results\Result;

class ResultTest extends TestCase
{
    protected Result $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->result = new Result('foo');
    }

    /**
     * @test
     */
    public function it_returns_a_placeholder(): void
    {
        self::assertSame('foo', $this->result->getPlaceholder());
    }

    /**
     * @test
     */
    public function it_adds_and_returns_data(): void
    {
        self::assertSame([], $this->result->getData());

        $this->result->addData('bar');

        self::assertSame(['bar'], $this->result->getData());
    }
}
