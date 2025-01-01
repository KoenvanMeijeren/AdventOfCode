<?php

namespace App\console\Y24\day6;

use App\shared\game\grid\GridPlayerTile;
use App\shared\game\grid\GridTile;
use App\shared\game\grid\IGrid;
use App\shared\game\grid\IGridObstacleTile;
use App\shared\game\player\IPlayer;
use App\shared\game\player\PlayerOutOfBoundsException;
use App\shared\game\player\PlayerVelocity;
use App\shared\game\position\Direction;
use App\shared\game\player\PlayerDirection;
use App\shared\game\position\Position;

/**
 * Provides the Guard.
 */
final class Guard implements IPlayer {

    private Position $initialPosition;
    private PlayerDirection $initialDirection;

    public function __construct(
        public Position $position,
        public PlayerDirection $direction,
        public PlayerVelocity $playerVelocity,
        public array $visitedTiles = [],
        public array $turnedAtTiles = [],
        public array $stuckAtPositions = [],
    ) {
        $this->initialPosition = $position;
        $this->initialDirection = $direction;
    }

    public static function fromGridValues(int $row, int $col, Direction $defaultDirection): self
    {
        $playerDirection = new PlayerDirection($defaultDirection);
        return new self(
            new Position($row, $col),
            $playerDirection,
            PlayerVelocity::fromPlayerDirection($playerDirection)
        );
    }

    public function move(IGrid $grid): void
    {
        $previousPosition = $this->position;
        $playerTile = $grid->getTile($previousPosition->row, $previousPosition->col);

        // Mark the previous position as visited.
        $previousPositionKey = $previousPosition->render();
        $this->visitedTiles[$previousPositionKey] ??= 0;
        $this->visitedTiles[$previousPositionKey]++;

        // Move the player to the next position and avoid obstacles.
        $nextPosition = $this->position->move($grid, $this->playerVelocity->rowSpeed, $this->playerVelocity->colSpeed);
        $nextTile = $grid->getTile($nextPosition->row, $nextPosition->col);
        while($nextTile instanceof IGridObstacleTile) {
            $nextPositionKey = $nextPosition->render();
            if (isset($this->turnedAtTiles[$nextPositionKey])
                && $this->turnedAtTiles[$nextPositionKey] >= 3) {
                throw new GuardIsStuckException();
            }

            // Mark the next position as turned at.
            $this->turnedAtTiles[$nextPositionKey] ??= 0;
            $this->turnedAtTiles[$nextPositionKey]++;

            $this->direction = $this->direction->turnCompleteRight();
            $this->playerVelocity = PlayerVelocity::fromPlayerDirection($this->direction);
            $nextPosition = $this->position->move($grid, $this->playerVelocity->rowSpeed, $this->playerVelocity->colSpeed);
            $nextTile = $grid->getTile($nextPosition->row, $nextPosition->col);
        }

        if (!$nextTile || !$playerTile || $grid->isOutOfBounds($nextPosition->row, $nextPosition->col)) {
            throw new PlayerOutOfBoundsException($nextPosition->row, $nextPosition->col);
        }

        // Construct the new tiles.
        $visitedTile = new GridTile($this->position, 'X');
        $playerTile = new GridPlayerTile($nextPosition, $this->render());

        // Swap the tiles.
        $this->position = $nextPosition;
        $grid->setTile($previousPosition->row, $previousPosition->col, $visitedTile);
        $grid->setTile($this->position->row, $this->position->col, $playerTile);
    }

    public function render(): string
    {
        return match ($this->direction->direction) {
            Direction::North, Direction::NorthEast, Direction::NorthWest => '^',
            Direction::South, Direction::SouthEast, Direction::SouthWest => 'v',
            Direction::East => '>',
            Direction::West => '<',
            default => throw new \Exception(sprintf('Unknown direction: %s', $this->direction->render())),
        };
    }

    public function getVisitedTilesCount(): int
    {
        return count($this->visitedTiles);
    }

    public function getStuckAtPositionsCount(): int
    {
        return count($this->stuckAtPositions);
    }

    public function resetPositionalData(): void
    {
        $this->position = clone $this->initialPosition;
        $this->direction = clone $this->initialDirection;
        $this->playerVelocity = PlayerVelocity::fromPlayerDirection($this->direction);
        $this->visitedTiles = [];
        $this->turnedAtTiles = [];
    }

}