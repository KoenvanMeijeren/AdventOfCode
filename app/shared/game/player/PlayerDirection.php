<?php

namespace App\shared\game\player;

use App\shared\game\IRenderable;
use App\shared\game\position\Direction;

final readonly class PlayerDirection implements IRenderable, \Stringable {

    public function __construct(
        public Direction $direction,
    ) {}

    public static function fromVelocity(int $rowSpeed, int $colSpeed): self
    {
        if ($rowSpeed === 0 && $colSpeed === 0) {
            throw new \InvalidArgumentException('Both rowSpeed and colSpeed cannot be zero.');
        }

        if ($rowSpeed < 0 && $colSpeed === 0) {
            return new self(Direction::North);
        }

        if ($rowSpeed < 0 && $colSpeed > 0) {
            return new self(Direction::NorthEast);
        }

        if ($rowSpeed === 0 && $colSpeed > 0) {
            return new self(Direction::East);
        }

        if ($rowSpeed > 0 && $colSpeed > 0) {
            return new self(Direction::SouthEast);
        }

        if ($rowSpeed > 0 && $colSpeed === 0) {
            return new self(Direction::South);
        }

        if ($rowSpeed > 0 && $colSpeed < 0) {
            return new self(Direction::SouthWest);
        }

        if ($rowSpeed === 0 && $colSpeed < 0) {
            return new self(Direction::West);
        }

        if ($rowSpeed < 0 && $colSpeed < 0) {
            return new self(Direction::NorthWest);
        }

        throw new \InvalidArgumentException(sprintf('Invalid rowSpeed and colSpeed: %d, %d', $rowSpeed, $colSpeed));
    }

    public function turnRight(): self
    {
        return new self($this->direction->turnRight());
    }

    /**
     * Turns the player 90 degrees to the right.
     */
    public function turnCompleteRight(): self
    {
        return new self($this->direction->turnCompleteRight());
    }

    public function turnLeft(): self
    {
        return new self($this->direction->turnLeft());
    }

    /**
     * Turns the player 90 degrees to the left.
     */
    public function turnCompleteLeft(): self
    {
        return new self($this->direction->turnCompleteLeft());
    }

    public function render(): string
    {
        return $this->direction->name;
    }

    public function __toString(): string
    {
        return $this->render();
    }

}