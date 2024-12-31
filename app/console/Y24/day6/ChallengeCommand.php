<?php

namespace  App\console\Y24\day6;

use App\shared\game\position\OutOfBoundsException;
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
        name: 'aoc:2024:day6',
        description: 'Runs the Day 6 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 6 of 2024...');

        $input = file_get_contents(__DIR__ . '/input.txt');
        $testInput = file_get_contents(__DIR__ . '/test-input.txt');

        $this->console->writeln('Test input game:');
        $this->renderGameForPart1($testInput, render: true);

        $this->console->writeln();
        $this->console->writeln('Input game:');
        $this->renderGameForPart1($input);
    }

    private function renderGameForPart1(string $input, bool $render = false): void
    {
        $game = new Game($this->console);
        $game->init($input);
        $game->render();

        $isGameStopped = false;
        $iterations = 0;
        while (!$isGameStopped) {
            try {
                $iterations++;
                $game->tick();
                if ($render) {
                    $game->render();
                }
            } catch (OutOfBoundsException $e) {
                $this->console->writeln($e->getMessage());
                $isGameStopped = true;
            }
        }

        $this->console->writeln();
        $this->console->writeln('Game stopped after ' . $iterations . ' iterations. Final state:');
        $game->render();

        $this->console->writeln();
        $this->console->writeln('Part 1 result: ' . $game->getUniqueVisitedTilesCount());
    }

}