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

    private function calculateMulFunctions(string $input): int {
        $result = 0;
        $regex = "/mul\((\d{1,3}),(\d{1,3})\)/";
        preg_match_all($regex, $input, $matches);
        foreach ($matches[0] as $index => $match) {
            $left = $matches[1][$index];
            $right = $matches[2][$index];
            if (!is_numeric($left) || !is_numeric($right)) {
                continue;
            }

            $result += $left * $right;
        }
        return $result;
    }

    private function calculateEnabledMulFunctions(string $input): int {
        $regex = "/don't\(\).*?do\(\)|don't\(\).*/";
        $enabledMulFunctions = preg_replace($regex, "", $input);
        return $this->calculateMulFunctions($enabledMulFunctions);
    }

}