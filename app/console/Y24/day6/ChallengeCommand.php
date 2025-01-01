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
        $this->renderGameForPart2($testInput, render: true);

        $this->console->writeln();
        $this->console->writeln('Input game:');
        $this->renderGameForPart1($input);
        $this->renderGameForPart2($input);
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
        $this->console->writeln('Part 1 result: ' . $game->getVisitedTilesCount());
    }

    private function renderGameForPart2(string $input, bool $render = false): void
    {
        // Init game.
        $game = new Game($this->console);
        $game->init($input);

        // Init guard player.
        $obstaclePlayer = ObstaclePlayer::fromGridValues(0, 0);
        $obstaclePlayer->init($game->grid);

        // Render initial state.
        $game->render();

        // Play the game.
        $isGameStopped = false;
        $iterations = 0;
        $moveObstaclePlayerCount = 0;
        $guardIsStuckCount = 0;
        while (!$isGameStopped) {
            try {
                $iterations++;
                $game->tick();
            } catch (OutOfBoundsException|GuardIsStuckException $exception) {
                try {
                    $moveObstaclePlayerCount++;

                    if ($exception instanceof GuardIsStuckException) {
                        $guardIsStuckCount++;
                    }

                    if ($render) {
                        $game->render();
                    }

                    // Move the obstacle player to a new position and reset the game on player out of bounds.
                    $game->reset();
                    $obstaclePlayer->move($game->grid);

                    $this->console->writeln('Obstacle player move count: ' . $moveObstaclePlayerCount);
                } catch (OutOfBoundsException $e) {
                    $this->console->writeln($e->getMessage());
                    $isGameStopped = true;
                }
            }
        }

        $this->console->writeln();
        $this->console->writeln('Game stopped after ' . $iterations . ' iterations. Final state:');
        $game->render();

        $this->console->writeln();
        $this->console->writeln('Part 2 result: ' . $guardIsStuckCount);
    }


}