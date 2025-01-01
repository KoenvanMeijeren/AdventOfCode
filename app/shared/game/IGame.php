<?php

namespace App\shared\game;

/**
 * Provides the IGame.
 */
interface IGame {

    /**
     * Updates the game state for the next tick.
     */
    public function tick(): void;

    /**
     * Updates the game state for the next tick and renders the game state.
     */
    public function tickAndRender(): void;

    /**
     * Resets the game state.
     */
    public function reset(): void;

    /**
     * Renders the game state.
     */
    public function render(): void;

}