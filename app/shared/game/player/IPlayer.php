<?php

namespace App\shared\game\player;

use App\shared\game\grid\IGrid;
use App\shared\game\grid\IGridTile;

/**
 * Provides an interface for IPlayer.
 */
interface IPlayer extends IGridTile {

    public function init(IGrid $grid): void;

    public function move(IGrid $grid): void;

}