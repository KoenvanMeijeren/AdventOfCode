<?php

namespace App\shared\game;

/**
 * Provides the IRenderable.
 */
interface IRenderable {

    /**
     * Renders the object.
     *
     * @return string The rendered object.
     */
    public function render(): string;

}