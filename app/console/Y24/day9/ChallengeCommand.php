<?php

namespace  App\console\Y24\day9;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

/**
 * Provides the Day1Command.
 */
final readonly class ChallengeCommand {

    public function __construct(
        private Console $console,
    ) {}

    #[ConsoleCommand(
        name: 'aoc:2024:day9',
        description: 'Runs the Day 9 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 9 of 2024...');

        // Test input.
        $testFilesystem = new Filesystem(
            __DIR__ . '/test-input.txt',
            fileDefragementer: new FileDefragementer($this->console),
        );
        $testFilesystem->buildDiskMap();
        $this->console->writeln('Test Disk Map:');
        $this->console->writeln($testFilesystem->toString());

        $this->console->writeln('Test Disk Map (Defragmented):');
        $testFilesystem->defragment(debug: true);
        $this->console->writeln($testFilesystem->toString());

        $this->console->writeln('Test Disk Map Checksum:');
        $this->console->writeln($testFilesystem->calculateChecksum());

        // Test input part 2.
        $this->console->writeln();
        $this->console->writeln('Running Part 2...');
        $testFilesystemPart2 = new Filesystem(
            __DIR__ . '/test-input.txt',
            fileDefragementer: new FileDefragementer($this->console),
        );
        $testFilesystemPart2->buildDiskMap();

        $this->console->writeln('Test Disk Map:');
        $this->console->writeln($testFilesystemPart2->toString());

        $this->console->writeln('Test Disk Map (Defragmented):');
        $testFilesystemPart2->defragment(debug: true);
        $this->console->writeln($testFilesystemPart2->toString());

        // Real input.
        $filesystem = new Filesystem(
            __DIR__ . '/input.txt',
            fileDefragementer: new FileDefragementer($this->console),
        );
        $filesystem->buildDiskMap();
        $filesystem->defragment();
        $this->console->writeln('Disk Map Checksum:');
        $this->console->writeln($filesystem->calculateChecksum());
    }

    private function oldCode(array $lines): void
    {
        $diskMap = [];
        foreach ($lines as $line) {
            $diskMapLine = $this->buildDiskMapLine($line);
            $this->console->writeln('Line: ' . substr($line, 0, 50) . '...');
            $diskMap[] = $diskMapLine;
        }

        $this->console->writeln();
        $this->console->writeln("Cleaning up disk map...");
        $cleanedDiskMap = [];
        foreach ($diskMap as $line) {
            $lineGapsCount = substr_count($line, '.');
            $lineLength = strlen($line);
            $this->console->writeln('Line: ' . substr($line, 0, 50) . '...');
            $cleanedDiskMapLine = $this->cleanupDiskMapLine($line, $lineGapsCount, $lineLength);
            $this->console->writeln('Cleaned line: ' . substr($cleanedDiskMapLine, 0, 50) . '...');
            $this->console->writeln();
            $cleanedDiskMap[] = $cleanedDiskMapLine;
        }

        foreach ($cleanedDiskMap as $line) {
            $result = $this->calculateDiskMapChecksum($line);

            $this->console->writeln('Line: ' . substr($line, 0, 50) . '...');
            $this->console->writeln('Result: ' . $result);
        }
    }

    private function calculateDiskMapChecksum(string $input): int
    {
        $result = 0;
        $inputLength = strlen($input);
        for ($index = 0; $index < $inputLength; $index++) {
            $char = $input[$index];
            if (is_numeric($char)) {
                $result += $index * $char;
            }
        }

        return $result;
    }

    private function cleanupDiskMapLine(string $input, int $gaps, int $inputLength, string $result = ''): string
    {
        // Initialize the result with the input if it's empty.
        $result = $result ?: $input;

        // Convert the string to an array for easier manipulation.
        $resultArray = str_split($result);

        while ($gaps > 0) {
            if ($gaps % 10 === 0) {
                $this->console->writeln('Gaps: ' . $gaps);
            }

            // Find the last non-gap character.
            $lastNumberIndex = null;
            for ($i = $inputLength - 1; $i >= 0; $i--) {
                if ($resultArray[$i] !== '.') {
                    $lastNumberIndex = $i;
                    break;
                }
            }

            // If no more non-gap characters exist, we're done.
            if ($lastNumberIndex === null) {
                break;
            }

            // Find the first gap character.
            $firstGapIndex = null;
            for ($i = 0; $i < $inputLength; $i++) {
                if ($resultArray[$i] === '.') {
                    $firstGapIndex = $i;
                    break;
                }
            }

            // If no gaps exist, we're done.
            if ($firstGapIndex === null) {
                break;
            }

            // Swap the characters.
            $resultArray[$firstGapIndex] = $resultArray[$lastNumberIndex];
            $resultArray[$lastNumberIndex] = '.';

            // Decrement the gaps counter.
            $gaps--;
        }

        // Convert the array back to a string.
        return implode('', $resultArray);
    }

    private function buildDiskMapLine(string $input): string
    {
        $result = '';
        $currentId = 0;
        $currentDisplayNumber = true;

        foreach (str_split($input) as $number) {
            $number = (int) $number;
            $result .= str_repeat($currentDisplayNumber ? (string) $currentId : '.', $number);

            // Update ID and toggle the display mode.
            if ($currentDisplayNumber) {
                $currentId++;
            }

            $currentDisplayNumber = !$currentDisplayNumber;
        }

        return $result;
    }

}