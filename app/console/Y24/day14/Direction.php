<?php

namespace App\console\Y24\day14;

final readonly class Direction {

    public function __construct(
        public RowDirection $row,
        public ColDirection $col,
    ) {}

    public static function fromVelocity(int $rowSpeed, int $colSpeed): self
    {
        $row = $rowSpeed > 0 ? RowDirection::Down : RowDirection::Up;
        $col = $colSpeed > 0 ? ColDirection::Right : ColDirection::Left;

        return new self($row, $col);
    }

    public function toString(): string
    {
        return sprintf('%s, %s', $this->row->name, $this->col->name);
    }

}

/**
 * Provides the Direction.
 */
enum RowDirection {
    case Up;
    case Down;
}

enum ColDirection {
    case Left;
    case Right;
}