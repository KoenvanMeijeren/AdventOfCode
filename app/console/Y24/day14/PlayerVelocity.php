<?php

namespace App\console\Y24\day14;

/**
 * Provides the PlayerVelocity.
 */
final readonly class PlayerVelocity {

    public function __construct(
        public Direction $direction,
        public int $rowSpeed,
        public int $colSpeed,
    ) {}

    public static function fromVelocity(int $rowSpeed, int $colSpeed): self
    {
        return new self(Direction::fromVelocity($rowSpeed, $colSpeed), $rowSpeed, $colSpeed);
    }

    public function toString(): string
    {
        return sprintf('v(%d,%d), d(%s)', $this->rowSpeed, $this->colSpeed, $this->direction->toString());
    }

}