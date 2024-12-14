<?php

namespace  App\console\Y24\day2;

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
        name: 'aoc:2024:day2',
        description: 'Runs the Day 2 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 2 of 2024...');

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        $this->ownTry($lines);
    }

    private function ownTry(array $lines): void {
        $result = 0;
        $resultPart2 = 0;
        foreach ($lines as $line) {
            $levels = array_map('intval', explode(' ', $line));
            $levelsCount = count($levels);
            if ($this->isSafeReport($levels)) {
                $result++;
                $resultPart2++;
                continue;
            }

            for ($i = 0; $i < $levelsCount; $i++) {
                $dampenedLevels = $levels;
                array_splice($dampenedLevels, $i, 1);
                if ($this->isSafeReport($dampenedLevels)) {
                    $resultPart2++;
                    break;
                }
            }
        }

        $this->console->writeln();
        $this->console->writeln('Result part 1: ' . $result);
        $this->console->writeln('Result part 2: ' . $resultPart2);
    }

    private function isSafeReport(array $levels): bool {
        $diffs = [];
        for ($i = 0; $i < count($levels) - 1; $i++) {
            $diffs[] = $levels[$i + 1] - $levels[$i];
        }

        $allNegative = array_reduce($diffs, static function($carry, $x) {
            return $carry && ($x < 0 && $x >= -3);
        }, true);

        $allPositive = array_reduce($diffs, static function($carry, $x) {
            return $carry && ($x > 0 && $x <= 3);
        }, true);

        return $allNegative || $allPositive;
    }

}