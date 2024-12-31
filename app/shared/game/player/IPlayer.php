<?php

namespace App\shared\game\player;

use App\shared\game\grid\IGrid;
use App\shared\game\grid\IGridTile;

/**
 * Provides an interface for IPlayer.
 */
interface IPlayer extends IGridTile {

    public function move(IGrid $grid): void;

}