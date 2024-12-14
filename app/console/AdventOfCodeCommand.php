<?php

namespace App\console;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

/**
 * Provides the AdventOfCodeCommand.
 */
final readonly class AdventOfCodeCommand {

    public function __construct(
        private Console $console,
    ) {}

    #[ConsoleCommand(
        name: 'aoc:all',
        description: 'Runs the Advent of Code 2024 challenges.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Advent of Code');
        $this->console->writeln();

        $this->console->writeln('Running the challenge of Day 1 in 2024...');
        $this->console->call('aoc:2024:day1');
    }

}