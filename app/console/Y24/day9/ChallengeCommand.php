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

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

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
            $cleanedDiskMapLine = $this->cleanupDiskMapLine($line, $lineGapsCount, $lineLength);
            $this->console->writeln('Line: ' . substr($line, 0, 50) . '...');
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
        // Initialize the result with the input if it's empty.
        $result = $result ?: $input;

        while ($gaps > 0) {
            $lastNumber = '';
            $lastNumberIndex = 0;
            $this->console->writeln('Gaps: ' . $gaps);

            // Find the last number.
            for ($i = $inputLength - 1; $i >= 0; $i--) {
                $char = $result[$i];
                if ($char !== '.') {
                    $lastNumber = $char;
                    $lastNumberIndex = $i;
                    break;
                }
            }

            // Swap the last number with the first gap char.
            for ($i = 0; $i < $inputLength; $i++) {
                $char = $result[$i];
                if ($char === '.') {
                    $result[$i] = $lastNumber;
                    $result[$lastNumberIndex] = '.';
                    break;
                }
            }

            // Decrement the gaps counter.
            $gaps--;
        }

        return $result;
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