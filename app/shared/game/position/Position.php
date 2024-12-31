<?php

namespace App\shared\game\position;

use App\shared\game\grid\IGrid;

/**
 * Provides the Position.
 */
final readonly class Position implements IPosition {

    public function __construct(
        public int $row,
        public int $col,
    ) {}

    public function move(IGrid $grid, int $rowSpeed, int $colSpeed, bool $wrapAroundEdges = false): self
    {
        $maxRow = $grid->rows;
        $maxCol = $grid->cols;

        // Calculate the new row and col based on velocity.
        $newRow = $this->row + $rowSpeed;
        $newCol = $this->col + $colSpeed;

        // Wrap row and col around the edges if desired.
        if ($wrapAroundEdges) {
            $newRow = ($newRow % ($maxRow + 1) + ($maxRow + 1)) % ($maxRow + 1);
            $newCol = ($newCol % ($maxCol + 1) + ($maxCol + 1)) % ($maxCol + 1);
        }

        return new self($newRow, $newCol);
    }

    public function render(): string
    {
        return "{$this->row},{$this->col}";
    }

    public function __toString(): string
    {
        return $this->render();
    }

}