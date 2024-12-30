<?php

namespace App\console\Y24\day9;

use Tempest\Console\Console;

final readonly class FileDefragementer implements FileDefragementerInterface
{

    public function __construct(
        private Console $console,
    ) {}

    public function hasGaps(Filesystem $filesystem): bool
    {
        $gaps = 0;
        $count = $filesystem->count();
        for ($i = 0; $i < $count - 1; $i++) {
            $file = $filesystem->getSector($i);
            $nextFile = $filesystem->getSector($i + 1);
            if ($nextFile === null) {
                return false;
            }

            if (!$file instanceof File || !$nextFile instanceof FreeSpace) {
                continue;
            }

            $gaps++;
            if ($gaps > 1) {
                return true;
            }
        }

        return false;
    }

    public function defragment(Filesystem $filesystem, bool $debug = false): void
    {
        $free = $filesystem->getFreeSpaces();
        $files = $filesystem->getFiles();

        $iterations = 0;
        while (!empty($free) && $this->hasGaps($filesystem)) {
            // Find the first free space.
            $freeData = reset($free);
            $freeIdx = key($free);
            unset($free[$freeIdx]);

            // Find the last file.
            $fileData = end($files);
            $fileIdx = key($files);
            unset($files[$fileIdx]);

            // Swap the file and free space.
            $filesystem->setSector($freeIdx, $fileData);
            $filesystem->setSector($fileIdx, $freeData);

            if ($debug) {
                $this->console->writeln($filesystem->toString());
            } else {
                $this->console->writeln('Defragmenting... ' . ++$iterations);
            }
        }
    }

}