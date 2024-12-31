<?php

namespace App\shared\game\position;

use App\shared\game\IRenderable;

/**
 * Provides the OutOfBoundsException.
 */
class OutOfBoundsException extends \RangeException implements IRenderable {

    public function __construct(
        public int $row,
        public int $col,
    ) {
        parent::__construct($this->render());
    }

    public function render(): string
    {
        return sprintf('Out of bounds: %d, %d', $this->row, $this->col);
    }

}