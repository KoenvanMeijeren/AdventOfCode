<?php

namespace App\console\Y24\day6;

use App\shared\game\grid\GridObstacleTile;
use App\shared\game\grid\GridTile;
use App\shared\game\grid\IGrid;
use App\shared\game\grid\IGridObstacleTile;
use App\shared\game\grid\IGridPlayerTile;
use App\shared\game\player\IPlayer;
use App\shared\game\player\ObstaclePlayerOutOfBoundsException;
use App\shared\game\position\Direction;
use App\shared\game\position\Position;

/**
 * Provides the Guard.
 */
final class ObstaclePlayer implements IPlayer {

    public function __construct(
        public Position $position,
    ) {}

    public static function fromGridValues(int $row, int $col): self
    {
        return new self(new Position($row, $col));
    }

    public function init(IGrid $grid): void
    {
        $grid->setTile($this->position->row, $this->position->col, new GridObstacleTile($this->position, $this->render()));
    }

    public function move(IGrid $grid): void
    {
        $previousPosition = $this->position;
        $playerTile = $grid->getTile($previousPosition->row, $previousPosition->col);

        // Move the player to the next position and avoid obstacles.
        $attempts = 0;
        $maxAttempts = 200;
        $nextPosition = $this->position->moveByDirection($grid, Direction::East, 1, moveUntilOutOfGridBounds: true);
        $nextTile = $grid->getTile($nextPosition->row, $nextPosition->col);
        while(($nextTile instanceof IGridObstacleTile
            || $nextTile instanceof IGridPlayerTile) && $attempts < $maxAttempts) {
            $nextPosition = $nextPosition->moveByDirection($grid, Direction::East, 1, moveUntilOutOfGridBounds: true);
            $nextTile = $grid->getTile($nextPosition->row, $nextPosition->col);
            $attempts++;
        }

        if ($attempts >= $maxAttempts) {
            throw new \OutOfBoundsException(sprintf('Could not find a valid position for the obstacle player after %d attempts. Current found position: %s', $maxAttempts, $nextPosition->render()));
        }

        if (!$nextTile || !$playerTile || $grid->isOutOfBounds($nextPosition->row, $nextPosition->col)) {
            throw new ObstaclePlayerOutOfBoundsException($nextPosition->row, $nextPosition->col);
        }

        $previousTile = new GridTile($previousPosition, $nextTile->render());
        $newTile = new GridObstacleTile($this->position, $this->render());

        // Swap the tiles.
        $this->position = $nextPosition;
        $grid->setTile($previousPosition->row, $previousPosition->col, $previousTile);
        $grid->setTile($this->position->row, $this->position->col, $newTile);
    }

    public function render(): string
    {
        return 'O';
    }

}