<?php

namespace  App\console\Y24\day3;

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
        name: 'aoc:2024:day3',
        description: 'Runs the Day 3 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 3 of 2024...');

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        $this->ownTry($lines);
    }

    private function ownTry(array $lines): void {
        $result = 0;
        $resultPart2 = 0;

        foreach ($lines as $line) {
            $result += $this->calculateMulFunctions($line);
            $resultPart2 += $this->calculateEnabledMulFunctions($line);
        }

        $this->console->writeln();
        $this->console->writeln('Result part 1: ' . $result);
        $this->console->writeln('Result part 2: ' . $resultPart2);
    }

    private function calculateMulFunctions(string $line): int {
        $result = 0;
        $matches = [];
        $regex = '/mul\(\d+,\d+\)/';
        preg_match_all($regex, $line, $matches);

        foreach ($matches[0] as $match) {
            $numbers = explode(',', substr($match, 4, -1));
            $result += $numbers[0] * $numbers[1];
        }

        return $result;
    }

    private function calculateEnabledMulFunctions(string $line): int {
        $result = 0;
        $validMuls = $this->getValidMulFunctions($line);
        foreach ($validMuls as $mul) {
            $numbers = explode(',', substr($mul, 4, -1));
            $result += $numbers[0] * $numbers[1];
        }

        return $result;
    }

    private function getValidMulFunctions(string $line): array {
        $matches = [];
        $regex = '/(?:do\(\)|don\'t\(\))|mul\(\d+,\d+\)/';
        preg_match_all($regex, $line, $matches);

        $instructions = $matches[0];
        $isMulEnabled = true;

        $result = [];
        $this->console->writeln();
        foreach ($instructions as $instruction) {
            $this->console->writeln($instruction);
            if (str_contains($instruction, 'do()')) {
                $isMulEnabled = true;
                continue;
            }

            if (str_contains($instruction, "don't()")) {
                $isMulEnabled = false;
                continue;
            }

            if ($isMulEnabled) {
                $result[] = $instruction;
            }
        }

        return $result;
    }

}