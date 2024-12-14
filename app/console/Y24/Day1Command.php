<?php

namespace  App\console\Y24;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

/**
 * Provides the Day1Command.
 */
final readonly class Day1Command {

    public function __construct(
        private Console $console,
    ) {}

    #[ConsoleCommand(
        name: 'aoc:2024:day1',
        description: 'Runs the Day 1 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Advent of Code 2024 - Day 1');
    }

}