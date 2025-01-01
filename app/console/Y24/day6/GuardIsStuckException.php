<?php

namespace App\console\Y24\day6;

/**
 * Provides the GuardIsStuckException.
 */
final class GuardIsStuckException extends \Exception {

    public function __construct() {
        parent::__construct('Cannot find a valid path. Guard is stuck.');
    }

}