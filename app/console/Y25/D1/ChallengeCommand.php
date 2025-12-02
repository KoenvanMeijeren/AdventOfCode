<?php

namespace  App\console\Y25\D1;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

/**
 * Provides the Day1Command.
 */
final class ChallengeCommand {

    /**
     * Provides the zeroCount.
     */
    private int $zeroCountPart2 = 0;

    public function __construct(
        private readonly Console $console,
    ) {}

    #[ConsoleCommand(
        name: 'aoc:2025:day1',
        description: 'Runs the Day 1 challenge of 2025.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 1 of 2025...');

        // Test cases.
        $this->console->writeln($this->dial(11, 'R8')); // 19
        $this->console->writeln($this->dial(0, 'L1')); // 99
        $this->console->writeln($this->dial(99, 'R1')); // 0
        $this->console->writeln($this->dial(99, 'L1')); // 98
        $this->console->writeln($this->dial(0, 'R1')); // 1
        $this->console->writeln($this->dial(5, 'L10')); // 95
        $this->console->writeln($this->dial(0, 'R520')); // 0
        $this->console->writeln($this->dial(0, 'L520')); // 0

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);
        $dial = 50;
        $zeroCount = 0;

        // Real results.
        $this->console->writeln();
        $this->console->writeln("Processing input...");
        $this->zeroCountPart2 = 0;
        foreach ($lines as $index => $line) {
            $this->console->writeln($line);

            $dial = $this->dial($dial, $line);
            if ($dial === 0) {
                $zeroCount++;
            }

            $this->console->writeln("Dial to: $dial");
        }

        $this->console->writeln();
        $this->console->writeln("Solution part 1: {$zeroCount}");
        $this->console->writeln("Solution part 2: {$this->zeroCountPart2}");
    }

    /**
     * Dial the number in the given direction.
     */
    private function dial(int $number, string $dialTo): int
    {
        $parts = str_split($dialTo);
        $direction = $parts[0];
        $distance = (int) substr($dialTo, 1);

        $result = $number;
        for ($i = $distance; $i > 0; $i--) {
            if ($direction === "R") {
                $result = ($result + 1) % 100;
            } else {
                $result = ($result - 1) % 100;

                if ($result < 0) {
                    $result = ($result + 100) % 100;
                }
            }

            if ($result === 0) {
                $this->zeroCountPart2++;
            }
        }

        return $result;
    }

}