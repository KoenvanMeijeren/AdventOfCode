<?php

namespace App\console\Y24\day9;

/**
 * Provides the FreeSpace.
 */
final readonly class FreeSpace implements \Stringable {

    public function __toString(): string
    {
        return '.';
    }

}