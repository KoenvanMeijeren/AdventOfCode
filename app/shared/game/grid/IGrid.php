<?php

namespace App\shared\game\grid;

use App\shared\game\IRenderable;

/**
 * Provides the IGrid.
 */
interface IGrid extends IRenderable {

    public static function fromArray(array $grid): self;

    public function getTile(int $row, int $col): ?IGridTile;

    public function setTile(int $row, int $col, IGridTile $tile): void;

    public function isOutOfBounds(int $row, int $col): bool;

}