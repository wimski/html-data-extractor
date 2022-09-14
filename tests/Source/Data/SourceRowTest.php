<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Source\Data;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\SourceRowGroupAlreadyExistsException;
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
    public function it_does_not_create_a_new_data_item_if_the_placeholder_already_exists(): void
    {
        $this->row->addData('lipsum', 'foo');

        self::assertCount(1, $this->row->getData());

        $this->row->addData('lipsum', 'bar');

        self::assertCount(1, $this->row->getData());
    }
}
