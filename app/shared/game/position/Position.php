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

    public function moveByDirection(IGrid $grid, Direction $direction, int $speed, bool $moveUntilOutOfGridBounds = false): self
    {
        $newRow = match ($direction) {
            Direction::North, Direction::NorthEast, Direction::NorthWest => $this->row - $speed,
            Direction::South, Direction::SouthEast, Direction::SouthWest => $this->row + $speed,
            default => $this->row,
        };

        $newCol = match ($direction) {
            Direction::East, Direction::NorthEast, Direction::SouthEast => $this->col + $speed,
            Direction::West, Direction::NorthWest, Direction::SouthWest => $this->col - $speed,
            default => $this->col,
        };

        // Move to the next row if the new col is out of bounds.
        if ($moveUntilOutOfGridBounds && $grid->isColOutOfBounds($newCol)) {
            $newRow = $this->row + 1;
            $newCol = 0;
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