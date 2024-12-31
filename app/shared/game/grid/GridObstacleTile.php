<?php

namespace App\shared\game\grid;

use App\shared\game\position\Position;

/**
 * Provides the GridTile.
 */
final readonly class GridObstacleTile implements IGridTile {

    public function __construct(
        public Position $position,
        public string $value,
    ) {}

    public static function fromGridValues(int $row, int $col, string $value): self
    {
        return new self(new Position($row, $col), $value);
    }

    public function render(): string
    {
        return $this->value;
    }

}