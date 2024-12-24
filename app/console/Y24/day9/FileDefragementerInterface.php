<?php

namespace App\console\Y24\day9;

/**
 * Provides an interface for FileDefragementerInterface.
 */
interface FileDefragementerInterface {

    public function defragment(Filesystem $filesystem): void;

}