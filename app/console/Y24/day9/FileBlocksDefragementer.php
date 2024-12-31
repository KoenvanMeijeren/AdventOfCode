<?php

namespace App\console\Y24\day9;

use Tempest\Console\Console;

final readonly class FileBlocksDefragementer implements FileDefragementerInterface
{

    public function __construct(
        private Console $console,
    ) {}

    public function defragment(Filesystem $filesystem, bool $debug = false): void
    {
        $free = $filesystem->getFreeSpaces();
        $files = $filesystem->getFiles();
        if (empty($free) || empty($files)) {
            return;
        }

        $fileBlocks = $this->getFileBlocks($filesystem);
        // Sort the file blocks by block id in descending order.
        usort($fileBlocks, static fn($a, $b) => $b['blockId'] <=> $a['blockId']);

        $iterations = 0;
        foreach ($fileBlocks as $fileBlock) {
            $files = $fileBlock['files'];
            $firstFileIndex = $fileBlock['startIndex'];
            $filesCount = count($files);
            $filesAdded = 0;

            for ($searchIndex = 0; $searchIndex < $firstFileIndex; $searchIndex++) {
                if ($filesAdded >= $filesCount) {
                    break;
                }

                $maxIndex = $searchIndex + $filesCount;
                if (!$this->isFreeSpace($filesystem, $searchIndex, $maxIndex)) {
                    continue;
                }

                foreach ($files as $file) {
                    $fileIndex = $file['index'];
                    $fileData = $file['file'];
                    $freeSpace = $filesystem->getSector($searchIndex);
                    if (!$freeSpace instanceof FreeSpace) {
                        continue;
                    }

                    // Swap the file and free space.
                    $filesystem->setSector($searchIndex, $fileData);
                    $filesystem->setSector($fileIndex, $freeSpace);

                    // Update the indexes and files added.
                    $searchIndex++;
                    $filesAdded++;
                }
            }

            if ($debug) {
                $this->console->writeln($filesystem->toString());
            } else {
                $this->console->writeln('Defragmenting... ' . ++$iterations);
            }
        }
    }

    public function getFileBlocks(Filesystem $filesystem): array
    {
        $result = [];
        $processed = [];

        $filesCount = $filesystem->count();
        for ($searchIndex = 0; $searchIndex < $filesCount; $searchIndex++) {
            $file = $filesystem->getSector($searchIndex);
            if (!$file instanceof File || isset($processed[$file->id])) {
                continue;
            }

            // Mark the file id as processed.
            $processed[$file->id] = true;

            $fileBlocks = $this->getMatchingFiles($filesystem, $file, $searchIndex + 1);
            if ($fileBlocks !== []) {
                $result[] = [
                    'blockId' => $file->id,
                    'startIndex' => $searchIndex,
                    'files' => $fileBlocks
                ];
            }
        }

        return $result;
    }

    public function getMatchingFiles(Filesystem $filesystem, File $file, int $startIndex): array
    {
        $result = [];
        $result[] = [
            'index' => $startIndex - 1,
            'id' => $file->id,
            'file' => $file,
        ];

        $filesCount = $filesystem->count();
        for ($searchIndex = $startIndex; $searchIndex < $filesCount; $searchIndex++) {
            $nextFile = $filesystem->getSector($searchIndex);
            if ($nextFile instanceof File && $file->id === $nextFile->id) {
                $result[] = [
                    'index' => $searchIndex,
                    'id' => $file->id,
                    'file' => $nextFile,
                ];
                continue;
            }

            break;
        }

        return $result;
    }

    private function isFreeSpace(Filesystem $filesystem, int $startIndex, int $endIndex): bool
    {
        for ($sector = $startIndex; $sector < $endIndex; $sector++) {
            if ($filesystem->getSector($sector) instanceof File) {
                return false;
            }
        }

        return true;
    }

}