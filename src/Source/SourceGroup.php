<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Source;

use Wimski\HtmlDataExtractor\Contracts\Source\SourceGroupInterface;
use Wimski\HtmlDataExtractor\Contracts\Source\SourceRowInterface;

class SourceGroup implements SourceGroupInterface
{
    /**
     * @var array<int, SourceRowInterface>
     */
    protected array $rows = [];

    public function __construct(
        protected string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function addRow(SourceRowInterface $row): void
    {
        $this->rows[] = $row;
    }
}
