<?php

namespace App\console\Y24\day9;

use Tempest\Console\Console;

final readonly class FileBlocksDefragementer implements FileDefragementerInterface
{

    public function __construct(
        private Console $console,
    ) {}

    public function getFreeGapBlocks(Filesystem $filesystem): array
    {
        $result = [];
        $count = $filesystem->count();
        for ($i = 0; $i < $count - 1; $i++) {
            $file = $filesystem->getSector($i);
            if (!$file instanceof File) {
                continue;
            }

            $gaps = $this->getNextFreeGaps($filesystem, $i + 1);
            if (count($gaps) > 1) {
                $result[] = [
                    'file' => $file,
                    'fileIndex' => $i,
                    'gaps' => $gaps
                ];
            }
        }

        return $result;
    }

    public function getNextFreeGaps(Filesystem $filesystem, int $startIdx): array
    {
        $result = [];
        $count = $filesystem->count();
        for ($i = $startIdx; $i < $count - 1; $i++) {
            $nextFile = $filesystem->getSector($i);
            if ($nextFile instanceof FreeSpace) {
                $result[] = [
                    'index' => $i,
                    'free' => $nextFile,
                ];
                continue;
            }

            break;
        }

        return $result;
    }

    public function getFileBlocks(Filesystem $filesystem): array
    {
        $result = [];
        $processedIds = [];

        $count = $filesystem->count();
        for ($i = 0; $i < $count - 1; $i++) {
            $file = $filesystem->getSector($i);
            if (!$file instanceof File || isset($processedIds[$file->id])) {
                continue;
            }

            // Mark the file id as processed.
            $processedIds[$file->id] = true;

            $fileBlocks = $this->getFileBlock($filesystem, $file, $i + 1);
            if ($fileBlocks !== []) {
                $result[] = [
                    'blockId' => $file->id,
                    'startIndex' => $i,
                    'files' => $fileBlocks
                ];
            }
        }

        return $result;
    }

    public function getFileBlock(Filesystem $filesystem, File $file, int $startIdx): array
    {
        $result = [];
        $result[] = [
            'index' => $startIdx - 1,
            'id' => $file->id,
            'file' => $file,
        ];

        $count = $filesystem->count();
        for ($i = $startIdx; $i < $count; $i++) {
            $nextFile = $filesystem->getSector($i);
            if ($nextFile instanceof File && $file->id === $nextFile->id) {
                $result[] = [
                    'index' => $i,
                    'id' => $file->id,
                    'file' => $nextFile,
                ];
                continue;
            }

            break;
        }

        return $result;
    }

    public function defragment(Filesystem $filesystem, bool $debug = false): void
    {
        $free = $filesystem->getFreeSpaces();
        $files = $filesystem->getFiles();
        if (empty($free) || empty($files)) {
            return;
        }

        $freeGapBlocks = $this->getFreeGapBlocks($filesystem);
        $fileBlocks = $this->getFileBlocks($filesystem);
        // Sort the file blocks by block id in descending order.
        usort($fileBlocks, static fn($a, $b) => $b['blockId'] <=> $a['blockId']);

        $iterations = 0;
        $areFreeGapsBlocksUpdated = true;
        while ($areFreeGapsBlocksUpdated) {
            // Mark as false to start the loop.
            $areFreeGapsBlocksUpdated = false;

            foreach ($freeGapBlocks as $freeGapBlockIndex => $freeGapBlock) {
                $gaps = $freeGapBlock['gaps'];
                $fileBlock = $this->getFileBlockToFillGap($fileBlocks, $freeGapBlock);
                if ($fileBlock === []) {
                    continue;
                }

                $fileBlockIndex = $fileBlock['index'];
                unset($fileBlocks[$fileBlockIndex]);
                $todoFiles = $fileBlock['files'];

                foreach ($gaps as $index => $gap) {
                    $gapIndex = $gap['index'];
                    $gapFile = $gap['free'];

                    // Extract the last file from the remaining files.
                    $todoFileBlock = end($todoFiles);
                    if (!$todoFileBlock) {
                        break;
                    }

                    // Remove the gap from the list.
                    unset($freeGapBlocks[$freeGapBlockIndex]['gaps'][$index]);

                    // Mark as updated and reloop through the behavior.
                    $areFreeGapsBlocksUpdated = true;

                    // Unset file from the list.
                    $todoFileIndex = key($todoFiles);
                    unset($todoFiles[$todoFileIndex]);

                    // Extract data.
                    $fileData = $todoFileBlock['file'];
                    $filesystemIndex = $todoFileBlock['index'];

                    // Swap the file and free space.
                    $filesystem->setSector($gapIndex, $fileData);
                    $filesystem->setSector($filesystemIndex, $gapFile);
                }

                if ($debug) {
                    $this->console->writeln($filesystem->toString());
                } else {
                    $this->console->writeln('Defragmenting... ' . ++$iterations);
                }
            }
        }
    }

    private function getFileBlockToFillGap(array $fileBlocks, array $freeGapBlock): array
    {
        $result = [];
        $gapCount = count($freeGapBlock['gaps']);
        foreach ($fileBlocks as $index => $fileBlock) {
            $files = $fileBlock['files'];
            $fileCount = count($files);
            // If files count is lower than gap count, allow.
            if ($fileCount <= $gapCount) {
                $result = [
                    'index' => $index,
                    'files' => $files
                ];
                break;
            }
        }

        return $result;
    }

}