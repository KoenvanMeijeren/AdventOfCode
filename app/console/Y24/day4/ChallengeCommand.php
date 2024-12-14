<?php

namespace  App\console\Y24\day4;

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
        name: 'aoc:2024:day4',
        description: 'Runs the Day 4 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 4 of 2024...');

        $input = file_get_contents(__DIR__ . '/test-input.txt');
        $lines = explode("\n", $input);

        $this->ownTry($lines);
    }

    private function ownTry(array $lines): void {
        $result = 0;
        $resultPart2 = 0;

        // This word search allows words to be horizontal, vertical,
        // diagonal, written backwards, or even overlapping other words.
        // It's a little unusual, though, as you don't merely need to
        // find one instance of XMAS - you need to find all of them.

        $linesCount = count($lines);
        for ($i = 0; $i < $linesCount; $i++) {
            $result += $this->findWordCountInLineAndLines($lines, $lines[$i]);
        }


        $this->console->writeln();
        $this->console->writeln('Result part 1: ' . $result);
    }

    private function findWordCountInLineAndLines(array $lines, string $line): int {
        $result = 0;
        $lineCount = strlen($line);
        $this->console->writeln('Line has ' . $lineCount . ' characters');

        // search in word
        $result += $this->findWordCountInLine($line);

        //


        return $result;
    }

    private function findWordCountInLine(string $line): int
    {
        $lineCount = strlen($line);
        $result = 0;
        for ($i = 0; $i < $lineCount; $i++) {
            $char = $line[$i];
            if ($char === 'X') {
                $nextChar = $line[$i + 1] ?? '';
                if ($nextChar === 'M') {
                    $nextChar = $line[$i + 2] ?? '';
                    if ($nextChar === 'A') {
                        $nextChar = $line[$i + 3] ?? '';
                        if ($nextChar === 'S') {
                            $result++;
                        }
                    }
                }
            }
        }

        return $result;
    }

}