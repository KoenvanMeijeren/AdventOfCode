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
        $this->console->writeln('Running AoC Day 14 of 2024...');

        $input = file_get_contents(__DIR__ . '/test-input.txt');
        $lines = explode("\n", $input);

        $players = [];
        $playersStrings = [];
        foreach ($lines as $line) {
            $player = Player::fromString($line);
            $players[] = $player;
            $playersStrings[] = $player->toString();
        }

        $this->console->writeln();
        $this->console->writeln('Players: ');
        foreach ($playersStrings as $playerString) {
            $this->console->writeln($playerString);
        }

        $this->console->writeln();
        $this->console->writeln('Game: ');
        $this->game->init($players);
        $this->game->render();

        for ($i = 0; $i < 100; $i++) {
            $this->console->writeln();
            $this->console->writeln('Tick: ' . $i + 1);
            $this->game->tick();
            $this->game->render();
        }
    }

}