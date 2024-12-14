<?php

namespace  App\console\Y24\day1;

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
        name: 'aoc:2024:day1',
        description: 'Runs the Day 1 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 1 of 2024...');

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        $this->ownTry($lines);
    }

    private function ownTry(array $lines): void {
        $leftTodo = [];
        $rightTodo = [];
        $rightAppearances = [];
        foreach ($lines as $line) {
            [$left, $right] = explode('   ', $line);

            $left = (int) $left;
            $right = (int) $right;

            $leftTodo[] = $left;
            $rightTodo[] = $right;

            $rightAppearances[$right] ??= 0;
            $rightAppearances[$right]++;
        }

        sort($leftTodo);
        sort($rightTodo);

        $result = 0;
        $resultPart2 = 0;
        $iMax = count($leftTodo);
        for ($i = 0; $i < $iMax; $i++) {
            $leftNumber = $leftTodo[$i];
            $rightNumber = $rightTodo[$i];
            $result += abs($leftNumber - $rightNumber);

            $rightAppearance = $rightAppearances[$leftNumber] ?? 0;
            $resultPart2 += $leftNumber * $rightAppearance;
        }

        $this->console->writeln('Result part 1: ' . $result);
        $this->console->writeln('Result part 2: ' . $resultPart2);
    }

    private function aiTry(array $lines): void {
        // Initialize collections
        $leftTodo = $rightTodo = $rightAppearances = [];

        // Process lines and collect data
        foreach ($lines as $line) {
            [$left, $right] = array_map('intval', explode('   ', $line));

            $leftTodo[] = $left;
            $rightTodo[] = $right;
            $rightAppearances[$right] = ($rightAppearances[$right] ?? 0) + 1;
        }

        // Sort left and right values
        sort($leftTodo);
        sort($rightTodo);

        // Calculate results
        $result = array_sum(array_map(static fn($l, $r) => abs($l - $r), $leftTodo, $rightTodo));
        $resultPart2 = array_sum(array_map(static fn($l) => $l * ($rightAppearances[$l] ?? 0), $leftTodo));

        // Output results
        $this->console->writeln('Result part 1: ' . $result);
        $this->console->writeln('Result part 2: ' . $resultPart2);
    }

}