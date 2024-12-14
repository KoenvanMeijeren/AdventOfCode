<?php

namespace App\console\Y24\day14;

use Tempest\Console\Console;

/**
 * Provides the Game.
 */
final class Game {

    /**
     * Constructs a new object.
     *
     * @param Player[] $players
     */
    public function __construct(
        private readonly Console $console,
        private int $width = 11,
        private int $height = 7,
        private array $grid = [],
        private array $players = [],
    ) {}

    /**
     * @param Player[] $players
     */
    public function init(array $players, int $width = 11, int $height = 7): void
    {
        $this->width = $width;
        $this->height = $height;
        $this->grid = array_fill(0, $this->height, array_fill(0, $this->width, 0));
        $this->players = $players;

        foreach ($this->players as $player) {
            $this->grid[$player->position->row][$player->position->col] ??= 0;
            $this->grid[$player->position->row][$player->position->col]++;
        }
    }

    public function tick(): void
    {
        $newGrid = array_fill(0, $this->height, array_fill(0, $this->width, 0));
        $maxRow = $this->height - 1;
        $maxCol = $this->width - 1;

        foreach ($this->players as $player) {
            $this->grid[$player->position->row][$player->position->col]--;
            $player->move($maxRow, $maxCol);
            $newGrid[$player->position->row][$player->position->col]++;
        }

        $this->grid = $newGrid;
    }

    public function render(): void
    {
        for ($row = 0; $row < $this->height; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                $gridValue = $this->grid[$row][$col];
                $this->console->write($gridValue === 0 ? '.' : $gridValue);
            }

            $this->console->writeln();
        }
    }

}