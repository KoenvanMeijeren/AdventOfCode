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

        $input = file_get_contents(__DIR__ . '/input.txt');
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
        $this->game->init($players, 101, 103);
        $this->game->render();

        for ($i = 0; $i < 100; $i++) {
            $this->console->writeln();
            $this->console->writeln('Tick: ' . $i + 1);
            $this->game->tick();
        }

        $this->console->writeln();
        $this->console->writeln('Game Over!');
        $this->console->writeln('Safest areas:');
        $this->game->renderSafestAreaGrid();

        // other idea: save 5x5 areas
        // Every iteration, I did a convolution on the tilemap to try and find a region of e.g. 5x5 of all robots.
        // If I found such a region, then it's highly likely part of the tree. With a kernel size of 5 (5x5 region)

        // brute force
        $this->console->writeln();
        $this->console->writeln('Part 2: Just render the game for 10_000 ticks... and find the result manually');
        for ($i = 0; $i < 10_000; $i++) {
            $this->game->tick();
            $this->console->writeln();
            $this->console->writeln('Tick: ' . $i + 1);
            $this->game->render();
        }

        // don't forget to add 100 seconds to your answer, because we're running part 2 after part 1
        $this->console->writeln();
        $this->console->writeln('Part 2');
        $this->console->writeln('Answer: x + 100');
    }

}