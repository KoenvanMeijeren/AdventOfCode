<?php

namespace App\shared\game\position;

/**
 * Provides the Direction.
 */
enum Direction {
    case North; // Up
    case NorthEast; // UpRight
    case East; // Right
    case SouthEast; // DownRight
    case South; // Down
    case SouthWest; // DownLeft
    case West; // Left
    case NorthWest; // UpLeft

    public function turnRight(): self
    {
        return match ($this) {
            self::North => self::NorthEast,
            self::NorthEast => self::East,
            self::East => self::SouthEast,
            self::SouthEast => self::South,
            self::South => self::SouthWest,
            self::SouthWest => self::West,
            self::West => self::NorthWest,
            self::NorthWest => self::North,
        };
    }

    /**
     * Turn the direction 90 degrees to the right.
     */
    public function turnCompleteRight(): self
    {
        return match ($this) {
            self::North, self::NorthEast => self::East,
            self::East, self::SouthEast => self::South,
            self::South, self::SouthWest => self::West,
            self::West, self::NorthWest => self::North,
        };
    }

    public function turnLeft(): self
    {
        return match ($this) {
            self::North => self::NorthWest,
            self::NorthWest => self::West,
            self::West => self::SouthWest,
            self::SouthWest => self::South,
            self::South => self::SouthEast,
            self::SouthEast => self::East,
            self::East => self::NorthEast,
            self::NorthEast => self::North,
        };
    }

    /**
     * Turn the direction 90 degrees to the left.
     */
    public function turnCompleteLeft(): self
    {
        return match ($this) {
            self::North, self::NorthWest => self::West,
            self::West, self::SouthWest => self::South,
            self::South, self::SouthEast => self::East,
            self::East, self::NorthEast => self::North,
        };
    }
}