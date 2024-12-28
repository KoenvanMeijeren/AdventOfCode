<?php

namespace  App\console\Y24\day5;

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
        name: 'aoc:2024:day5',
        description: 'Runs the Day 5 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 5 of 2024...');

        $testInput = file_get_contents(__DIR__ . '/test-input.txt');
        $input = file_get_contents(__DIR__ . '/input.txt');
        $testLines = explode("\n", $testInput);
        $lines = explode("\n", $input);

        // Calculate the puzzle answers for the test input.
        $testPrintQueue = PrintQueue::fromLines($testLines);
        $correctlySortedBooks = $testPrintQueue->getCorrectlySortedBooks();
        $sortedBooks = $testPrintQueue->sortIncorrectlySortedBooks();

        $this->console->writeln();
        $this->console->writeln('Results for test print queue:');
        $this->console->writeln($testPrintQueue);

        $this->console->writeln();
        $this->console->writeln('Correctly sorted books:');
        $this->console->writeln(implode("\n", $correctlySortedBooks));

        $this->console->writeln();
        $this->console->writeln('Sort incorrectly sorted books:');
        $this->console->writeln(implode("\n", $sortedBooks));

        $this->console->writeln();
        $this->console->writeln('Test result part 1: ' . $testPrintQueue->getPuzzleAnswer($correctlySortedBooks));
        $this->console->writeln('Test result part 2: ' . $testPrintQueue->getPuzzleAnswer($sortedBooks));

        // Calculate the puzzle answers for the input.
        $this->console->writeln();
        $this->console->writeln('Results for print queue');
        $printQueue = PrintQueue::fromLines($lines);
        $correctlySortedBooks = $printQueue->getCorrectlySortedBooks();
        $sortedBooks = $printQueue->sortIncorrectlySortedBooks();

        $this->console->writeln();
        $this->console->writeln('Result part 1: ' . $printQueue->getPuzzleAnswer($correctlySortedBooks));
        $this->console->writeln('Result part 2: ' . $printQueue->getPuzzleAnswer($sortedBooks));
    }

}