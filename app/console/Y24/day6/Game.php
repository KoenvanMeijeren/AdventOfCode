<?php

namespace App\console\Y24\day6;

use App\shared\game\grid\Grid;
use App\shared\game\grid\GridObstacleTile;
use App\shared\game\grid\GridTile;
use App\shared\game\grid\IGrid;
use App\shared\game\IGame;
use App\shared\game\position\Direction;
use Tempest\Console\Console;

/**
 * Provides the Game.
 */
final class Game implements IGame {

    private IGrid $initialGrid;
    public IGrid $grid;
    public Guard $guard;

    public function __construct(
        private readonly Console $console,
    ) {}

    public function init(string $input): void
    {
        $this->grid = Grid::fromArray(array_map(
            fn($row, $line) => array_map(
                fn($col, $tileString) => match ($tileString) {
                    '.' => GridTile::fromGridValues($row, $col, '.'),
                    '#' => GridObstacleTile::fromGridValues($row, $col, '#'),
                    '^' => $this->initGuard($row, $col),
                    default => throw new \Exception("Unknown tile type: $tileString. Supported types are: ., #, ^"),
                },
                array_keys(str_split($line)),
                str_split($line)
            ),
            array_keys($lines = explode("\n", $input)),
            $lines
        ));
        $this->initialGrid = clone $this->grid;
    }

    public function tick(): void
    {
        $this->guard->move($this->grid);
    }

    public function tickAndRender(): void
    {
        $this->tick();
        $this->render();
    }

    public function render(): void
    {
        $this->console->writeln($this->grid->render());
    }

    public function getVisitedTilesCount(): int
    {
        return $this->guard->getVisitedTilesCount();
    }

    public function getStuckAtPositionsCount(): int
    {
        return $this->guard->getStuckAtPositionsCount();
    }

    private function initGuard(int $row, int $col): Guard
    {
        $this->guard = Guard::fromGridValues($row, $col, Direction::North);

        return $this->guard;
    }

}