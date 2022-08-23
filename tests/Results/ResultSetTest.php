<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Results;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\ResultNotFoundException;
use Wimski\HtmlDataExtractor\Results\Result;
use Wimski\HtmlDataExtractor\Results\ResultSet;

class ResultSetTest extends TestCase
{
    protected ResultSet $resultSet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resultSet = new ResultSet();
    }

    /**
     * @test
     */
    public function it_adds_an_returns_a_result(): void
    {
        $result = new Result('foo');

        $this->resultSet->addResult($result);

        self::assertSame($result, $this->resultSet->getResult('foo'));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_a_result_cannot_be_found(): void
    {
        self::expectException(ResultNotFoundException::class);

        $this->resultSet->getResult('bar');
    }
}
