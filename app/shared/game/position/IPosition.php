<?php

namespace App\shared\game\position;

use App\shared\game\grid\IGrid;
use App\shared\game\IRenderable;

/**
 * Provides the IPosition.
 */
interface IPosition extends IRenderable, \Stringable {

    public function move(IGrid $grid, int $rowSpeed, int $colSpeed, bool $wrapAroundEdges = false): self;

    public function moveByDirection(IGrid $grid, Direction $direction, int $speed, bool $moveUntilOutOfGridBounds = false): self;

}