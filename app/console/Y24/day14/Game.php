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

    public function renderSafestAreaGrid(): void
    {
        $safestAreasGrid = $this->grid;
        $heightCenter = intdiv($this->height, 2);
        $widthCenter = intdiv($this->width, 2);

        for ($row = 0; $row < $this->height; $row++) {
            if ($row === $heightCenter) {
                $safestAreasGrid[$row] = array_fill(0, $this->width, ' ');
            }

            for ($col = 0; $col < $this->width; $col++) {
                if ($col === $widthCenter) {
                    $safestAreasGrid[$row][$col] = ' ';
                }
            }
        }

        $robotsInQuadrants = [0, 0, 0, 0];
        for ($row = 0; $row < $this->height; $row++) {
            if ($row === $heightCenter) {
                continue;
            }

            $isUpperQuadrant = $row > $heightCenter;

            for ($col = 0; $col < $this->width; $col++) {
                if ($col === $widthCenter) {
                    continue;
                }

                $gridValue = $this->grid[$row][$col];
                $isLeftQuadrant = $col > $widthCenter;

                if (!is_numeric($gridValue)) {
                    continue;
                }

                $quadrantRowIndex = 'upper';
                if (!$isUpperQuadrant) {
                    $quadrantRowIndex = 'lower';
                }

                $quadrantColIndex = 'left';
                if (!$isLeftQuadrant) {
                    $quadrantColIndex = 'right';
                }

                $quadrantIndex = match ($quadrantRowIndex . $quadrantColIndex) {
                    'upperleft' => 0,
                    'upperright' => 1,
                    'lowerleft' => 2,
                    'lowerright' => 3,
                };

                $robotsInQuadrants[$quadrantIndex] ??= 0;
                $robotsInQuadrants[$quadrantIndex] += $gridValue;
            }
        }

        for ($row = 0; $row < $this->height; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                $gridValue = $safestAreasGrid[$row][$col];
                $this->console->write($gridValue === 0 ? '.' : $gridValue);
            }

            $this->console->writeln();
        }

        $result = array_product($robotsInQuadrants);
        $this->console->writeln();
        $this->console->writeln('Result: ' . $result);
    }

}