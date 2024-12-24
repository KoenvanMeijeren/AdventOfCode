<?php

namespace App\console\Y24\day9;

/**
 * Provides the Filesystem.
 */
final class Filesystem {

    /**
     * @var array<File|FreeSpace>
     */
    private array $diskMap;

    public function __construct(
        private readonly string $filename,
        private FileDefragementerInterface $fileDefragementer
    ) {}

    public function buildDiskMap(): void
    {
        $content = (string) file_get_contents($this->filename);
        $fileLength = strlen($content);
        $currentDisplayNumber = true;
        $fileId = -1;

        for ($i = 0; $i < $fileLength; $i++) {
            $number = (int) $content[$i];
            if ($currentDisplayNumber) {
                $fileId++;
            }

            // Add the number of files or free spaces to the disk map
            for ($j = 0; $j < $number; $j++) {
                $this->diskMap[] = $currentDisplayNumber ? new File($fileId) : new FreeSpace();
            }

            // Toggle the display number mode
            $currentDisplayNumber = !$currentDisplayNumber;
        }
    }

    public function calculateChecksum(): int
    {
        $files = $this->getFiles();

        $sum = 0;
        foreach ($files as $index => $file) {
            $sum += $index * $file->id;
        }

        return $sum;
    }

    public function defragment(): void
    {
        $this->fileDefragementer->defragment($this);
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return array_filter($this->diskMap, static fn($item) => $item instanceof File);
    }

    public function getFreeSpaces(): array
    {
        return array_filter($this->diskMap, static fn($item) => $item instanceof FreeSpace);
    }

    public function count(): int
    {
        return count($this->diskMap);
    }

    public function getSector(int $index): File|FreeSpace|null
    {
        return $this->diskMap[$index] ?? null;
    }

    public function setSector(int $index, File|FreeSpace $sector): void
    {
        $this->diskMap[$index] = $sector;
    }

    public function toString(): string
    {
        return implode('', $this->diskMap);
    }

}