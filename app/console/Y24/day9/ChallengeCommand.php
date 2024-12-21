<?php

namespace  App\console\Y24\day9;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

ini_set('memory_limit', '8G');

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

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        $diskMap = [];
        foreach ($lines as $line) {
            $diskMapLine = $this->buildDiskMapLine($line);
            $this->console->writeln($diskMapLine);
            $diskMap[] = $diskMapLine;
        }

        $this->console->writeln();
        $this->console->writeln("Cleaning up disk map...");
        $cleanedDiskMap = [];
        foreach ($diskMap as $line) {
            $lineGapsCount = substr_count($line, '.');
            $lineLength = strlen($line);
            $cleanedDiskMapLine = $this->cleanupDiskMapLine($line, $lineGapsCount, $lineLength);
            $this->console->writeln($cleanedDiskMapLine);
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
        $multiplier = 0;
        for ($i = 0; $i < $inputLength; $i++) {
            $char = $input[$i];
            if (is_numeric($char)) {
                $result += $multiplier * $char;
                $multiplier++;
            }
        }

        return $result;
    }

    private function cleanupDiskMapLine(string $input, int $gaps, int $inputLength, string $result = ''): string
    {
        // Base case.
        if ($gaps < 1) {
            return $result;
        }

        // Initialize the result.
        if (empty($result)) {
            $result = $input;
        }

        // Find the last number.
        $lastNumber = '';
        $lastNumberIndex = 0;
        for ($i = $inputLength - 1; $i >= 0; $i--) {
            $char = $input[$i];
            if ($char !== '.') {
                $lastNumber = $char;
                $lastNumberIndex = $i;
                break;
            }
        }

        // Swap the last number with the first gap char.
        for ($i = 0; $i < $inputLength; $i++) {
            $char = $input[$i];
            if ($char === '.') {
                $result[$i] = $lastNumber;
                $result[$lastNumberIndex] = '.';
                break;
            }
        }

        $this->console->writeln($result);
        return $this->cleanupDiskMapLine($result, $gaps - 1, $inputLength, $result);
    }

    private function buildDiskMapLine(string $input, int $id = 0, string $result = '', bool $displayNumber = true): string
    {
        // Base case.
        $number = $input[0] ?? null;
        if (is_null($number)) {
            return $result;
        }

        // Build the disk map.
        for ($i = 0; $i < $number; $i++) {
            if ($displayNumber) {
                $result .= $id;
                continue;
            }

            $result .= '.';
        }

        // Prepare for next iteration.
        $nextId = $id;
        if ($displayNumber) {
            $nextId++;
        }

        return $this->buildDiskMapLine(substr($input, 1), $nextId, $result, !$displayNumber);
    }

}