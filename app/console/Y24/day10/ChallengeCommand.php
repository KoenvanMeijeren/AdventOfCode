<?php

namespace  App\console\Y24\day10;

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
        name: 'aoc:2024:day10',
        description: 'Runs the Day 10 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 10 of 2024...');

        $input = file_get_contents(__DIR__ . '/test-input.txt');
        $lines = explode("\n", $input);

        foreach ($lines as $line) {
            $this->console->writeln($line);

        }

    }

}