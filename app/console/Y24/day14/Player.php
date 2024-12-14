<?php

namespace App\console\Y24\day14;

/**
 * Provides the Player.
 */
final readonly class Player {

    public function __construct(
        public int $pRow,
        public int $pCol,
        public int $vRow,
        public int $vCol,
        public string $raw = '',
    ) {}

    public static function fromString(string $line): Player
    {
        [$pString, $vString] = explode(' ', $line);
        [$pCol, $pRow] = explode(',', substr($pString, 2));
        [$vCol, $vRow] = explode(',', substr($vString, 2));

        return new self($pRow, $pCol, $vRow, $vCol, $line);
    }

}