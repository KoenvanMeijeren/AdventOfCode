<?php

namespace App\shared\game\player;

use App\shared\game\position\OutOfBoundsException;

/**
 * Provides the OutOfBoundsException.
 */
final class ObstaclePlayerOutOfBoundsException extends OutOfBoundsException {

    public function render(): string
    {
        return sprintf('Cannot move the obstacle player to the next position. Out of bounds: (%s,%s)', $this->row, $this->col);
    }

}