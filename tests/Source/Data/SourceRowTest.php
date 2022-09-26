<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Source\Data;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowDataDoesNotExistException;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupDoesNotExistException;
use Wimski\HtmlDataExtractor\Source\Data\SourceGroup;
use Wimski\HtmlDataExtractor\Source\Data\SourceRow;

class SourceRowTest extends TestCase
{
    protected SourceRow $row;

    protected function setUp(): void
    {
        parent::setUp();

        $this->row = new SourceRow();
    }

    /**
     * @test
     */
    public function it_gets_a_group_by_name(): void
    {
        $group = new SourceGroup('foo');

        $this->row->addGroup($group);

        self::assertSame($group, $this->row->getGroupByName('foo'));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_a_group_cannot_be_found_by_name(): void
    {
        self::expectException(SourceRowGroupDoesNotExistException::class);

        $this->row->getGroupByName('foo');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_adding_a_group_with_a_name_that_already_exists(): void
    {
        self::expectException(SourceRowGroupAlreadyExistsException::class);

        $group = new SourceGroup('foo');

        $this->row->addGroup($group);

        self::assertCount(1, $this->row->getGroups());

        $this->row->addGroup($group);
    }

    /**
     * @test
     */
    public function it_gets_the_first_data_value_by_placeholder(): void
    {
        $this->row->addData('foo', 'bar');
        $this->row->addData('foo', 'lipsum');

        self::assertSame('bar', $this->row->getFirstDataValueByPlaceholder('foo'));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_data_cannot_be_found_by_placeholder(): void
    {
        self::expectException(SourceRowDataDoesNotExistException::class);

        $this->row->getFirstDataValueByPlaceholder('foo');
    }

    /**
     * @test
     */
    public function it_does_not_create_a_new_data_item_if_the_placeholder_already_exists(): void
    {
        $this->row->addData('lipsum', 'foo');

        self::assertCount(1, $this->row->getData());

        $this->row->addData('lipsum', 'bar');

        self::assertCount(1, $this->row->getData());
    }
}
