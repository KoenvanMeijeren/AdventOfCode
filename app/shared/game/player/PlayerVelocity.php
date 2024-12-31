<?php

namespace App\shared\game\player;

use App\shared\game\position\Direction;

/**
 * Provides the PlayerVelocity.
 */
final readonly class PlayerVelocity {

    public function __construct(
        public PlayerDirection $direction,
        public int $rowSpeed,
        public int $colSpeed,
    ) {}

    public static function fromVelocity(int $rowSpeed, int $colSpeed): self
    {
        return new self(PlayerDirection::fromVelocity($rowSpeed, $colSpeed), $rowSpeed, $colSpeed);
    }

    public static function fromPlayerDirection(PlayerDirection $direction): PlayerVelocity
    {
        return match ($direction->direction) {
            Direction::North => new PlayerVelocity($direction, -1, 0),
            Direction::NorthEast => new PlayerVelocity($direction, -1, 1),
            Direction::NorthWest => new PlayerVelocity($direction, -1, -1),
            Direction::South => new PlayerVelocity($direction, 1, 0),
            Direction::SouthEast => new PlayerVelocity($direction, 1, 1),
            Direction::SouthWest => new PlayerVelocity($direction, 1, -1),
            Direction::East => new PlayerVelocity($direction, 0, 1),
            Direction::West => new PlayerVelocity($direction, 0, -1),
            default => throw new \Exception(sprintf('Unsupported direction: %s', $direction->render())),
        };
    }

    public function toString(): string
    {
        return sprintf('v(%d,%d), d(%s)', $this->rowSpeed, $this->colSpeed, $this->direction->render());
    }

}