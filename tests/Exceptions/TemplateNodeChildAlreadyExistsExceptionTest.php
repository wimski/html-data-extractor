<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Contracts\Template\TemplateNodeInterface;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeChildAlreadyExistsException;

class TemplateNodeChildAlreadyExistsExceptionTest extends TestCase
{
    protected TemplateNodeChildAlreadyExistsException $exception;
    protected TemplateNodeInterface $parent;
    protected TemplateNodeInterface $child;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parent    = $this->createMock(TemplateNodeInterface::class);
        $this->child     = $this->createMock(TemplateNodeInterface::class);
        $this->exception = new TemplateNodeChildAlreadyExistsException(
            $this->parent,
            $this->child,
        );
    }

    /**
     * @test
     */
    public function it_returns_a_message(): void
    {
        self::assertSame('The template node already has a similar child', $this->exception->getMessage());
    }

    /**
     * @test
     */
    public function it_returns_a_parent(): void
    {
        self::assertSame($this->parent, $this->exception->getParent());
    }

    /**
     * @test
     */
    public function it_returns_a_child(): void
    {
        self::assertSame($this->child, $this->exception->getChild());
    }
}
