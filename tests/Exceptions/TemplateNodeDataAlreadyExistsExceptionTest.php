<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Contracts\Template\Data\TemplateDataInterface;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeDataAlreadyExistsException;

class TemplateNodeDataAlreadyExistsExceptionTest extends TestCase
{
    protected TemplateNodeDataAlreadyExistsException $exception;
    protected TemplateNodeInterface $node;
    protected TemplateDataInterface $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->node      = $this->createMock(TemplateNodeInterface::class);
        $this->data      = $this->createMock(TemplateDataInterface::class);
        $this->exception = new TemplateNodeDataAlreadyExistsException(
            $this->node,
            $this->data,
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('The template node already has similar data', $this->exception->getMessage());
    }

    /**
     * @test
     */
    public function it_returns_a_node(): void
    {
        self::assertSame($this->node, $this->exception->getNode());
    }

    /**
     * @test
     */
    public function it_returns_data(): void
    {
        self::assertSame($this->data, $this->exception->getData());
    }
}
