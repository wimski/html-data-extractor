<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Contracts\Source\Data;

interface SourceGroupInterface
{
    public function getName(): string;

    /**
     * @return array<int, SourceRowInterface>
     */
    public function getRows(): array;
    public function addRow(SourceRowInterface $row): void;
}
