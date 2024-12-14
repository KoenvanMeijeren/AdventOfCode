<?php

namespace  App\console\Y24\day14;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

/**
 * Provides the Day1Command.
 */
final readonly class ChallengeCommand {

    private Game $game;

    public function __construct(
        private Console $console,
    ) {
        $this->game = new Game($console);
    }

    #[ConsoleCommand(
        name: 'aoc:2024:day14',
        description: 'Runs the Day 14 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        ray()->clearAll();
        $this->console->writeln('Running AoC Day 14 of 2024...');

        $input = file_get_contents(__DIR__ . '/test-input.txt');
        $lines = explode("\n", $input);

        $players = [];
        foreach ($lines as $line) {
            $players[] = Player::fromString($line);
        }

        $this->game->init($players);
        $this->game->render();
    }

}