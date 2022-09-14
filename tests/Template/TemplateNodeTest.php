<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Template;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeChildAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeDataAlreadyExistsException;
use Wimski\HtmlDataExtractor\Template\Data\TemplateAttributeData;
use Wimski\HtmlDataExtractor\Template\Data\TemplateTextData;
use Wimski\HtmlDataExtractor\Template\TemplateNode;

class TemplateNodeTest extends TestCase
{
    protected TemplateNode $node;

    protected function setUp(): void
    {
        parent::setUp();

        $this->node = new TemplateNode('#id.class');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_adding_a_child_with_a_selector_that_already_exists(): void
    {
        self::expectException(TemplateNodeChildAlreadyExistsException::class);

        $child = new TemplateNode('foo');

        $this->node->addChild($child);
        $this->node->addChild($child);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_adding_a_data_item_with_a_placeholder_that_already_exists(): void
    {
        self::expectException(TemplateNodeDataAlreadyExistsException::class);

        $data = new TemplateTextData('foo');

        $this->node->addData($data);
        $this->node->addData($data);
    }

    /**
     * @test
     */
    public function it_transforms_to_an_array(): void
    {
        $this->node->addData(new TemplateTextData('foo'));
        $this->node->addData(new TemplateAttributeData('bar', 'src'));

        $child = new TemplateNode('child');
        $child->makeGroup('collection');

        $this->node->addChild($child);

        self::assertSame([
            'selector' => '#id.class',
            'group'    => null,
            'nodes'    => [
                [
                    'selector' => 'child',
                    'group'    => 'collection',
                    'nodes'    => [],
                    'data'     => [],
                ],
            ],
            'data'     => [
                'foo',
                'bar',
            ],
        ], $this->node->toArray());
    }
}
