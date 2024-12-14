<?php

namespace App\console\Y24\day14;

use function PHPUnit\Framework\matches;

/**
 * Provides the Player.
 */
final class Player {

    public function __construct(
        public Position $position,
        public readonly PlayerVelocity $velocity,
        public readonly string $raw = '',
    ) {}

    public static function fromString(string $line): Player
    {
        [$pString, $vString] = explode(' ', $line);
        [$pCol, $pRow] = explode(',', substr($pString, 2));
        [$vCol, $vRow] = explode(',', substr($vString, 2));
        $position = new Position((int) $pRow, (int) $pCol);
        $velocity = PlayerVelocity::fromVelocity((int) $vRow, (int) $vCol);

        return new self($position, $velocity, $line);
    }

    public function move(int $maxRow, int $maxCol): void
    {
        $this->position = $this->position->move($this->velocity, $maxRow, $maxCol);
    }

    public function toString(): string
    {
        return "p({$this->position->toString()}, {$this->velocity->toString()}";
    }

}