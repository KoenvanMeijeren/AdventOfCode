<?php

namespace App\console\Y24\day14;

use function PHPUnit\Framework\matches;

/**
 * Provides the Position.
 */
final readonly class Position {

    public function __construct(
        public int $row,
        public int $col,
    ) {}

    public function move(PlayerVelocity $velocity, int $maxRow, int $maxCol): self
    {
        // Calculate the new row based on velocity
        $newRow = $this->row + $velocity->rowSpeed;

        // Wrap row around the edges
        $newRow = ($newRow % ($maxRow + 1) + ($maxRow + 1)) % ($maxRow + 1);

        // Calculate the new column based on velocity
        $newCol = $this->col + $velocity->colSpeed;

        // Wrap column around the edges
        $newCol = ($newCol % ($maxCol + 1) + ($maxCol + 1)) % ($maxCol + 1);

        // Return the new position as a new object
        return new self($newRow, $newCol);
    }

    public function toString(): string
    {
        return "{$this->row},{$this->col}";
    }

}