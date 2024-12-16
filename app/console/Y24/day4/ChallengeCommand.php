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

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        $this->ownTry($lines);
    }

    private function ownTry(array $lines): void {
        $this->console->writeln();
        $this->console->writeln('Counting words in lines...');
        $result = $this->searchAndCountWordInLinesTry1($lines);
        $resultTry2 = $this->searchAndCountWordInLinesTry2($lines);
        $resultPart2 = $this->searchAndCountWordInLinesPart2($lines);

        $this->console->writeln();
        $this->console->writeln('Result try 1 part 1: ' . $result);
        $this->console->writeln('Result try 2 part 1: ' . $resultTry2);
        $this->console->writeln('Result part 2: ' . $resultPart2);
    }

    private function searchAndCountWordInLinesTry2(array $lines): int {
        $result = 0;

        $directions = [
            [0, 1],  // Right
            [0, -1], // Left
            [1, 0],  // Down
            [-1, 0], // Up
            [1, 1],  // Down-Right
            [-1, -1],// Up-Left
            [1, -1], // Down-Left
            [-1, 1], // Up-Right
        ];

        $rowCount = count($lines);
        $colCount = strlen($lines[0]);
        for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
            for ($colIndex = 0; $colIndex < $colCount; $colIndex++) {
                foreach ($directions as $direction) {
                    $rowDirection = $direction[0];
                    $colDirection = $direction[1];

                    if ($this->searchWordByDirection($lines, $rowIndex, $colIndex, $rowDirection, $colDirection, 'XMAS')) {
                        $result++;
                    }
                }
            }
        }

        return $result;
    }

    private function searchAndCountWordInLinesPart2(array $lines): int {
        $result = 0;

        $rowCount = count($lines);
        $colCount = strlen($lines[0]);
        for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
            for ($colIndex = 0; $colIndex < $colCount; $colIndex++) {
                $char = $lines[$rowIndex][$colIndex];
                if ($char === 'A') {
                    $upperLeftChar = $lines[$rowIndex - 1][$colIndex - 1] ?? '';
                    $upperRightChar = $lines[$rowIndex - 1][$colIndex + 1] ?? '';
                    $lowerLeftChar = $lines[$rowIndex + 1][$colIndex - 1] ?? '';
                    $lowerRightChar = $lines[$rowIndex + 1][$colIndex + 1] ?? '';

                    if ($upperLeftChar === 'M' && $upperRightChar === 'S'
                        && $lowerLeftChar === 'M' && $lowerRightChar === 'S') {
                        $result++;
                    }

                    if ($upperLeftChar === 'S' && $upperRightChar === 'M'
                        && $lowerLeftChar === 'S' && $lowerRightChar === 'M') {
                        $result++;
                    }

                    if ($upperLeftChar === 'S' && $upperRightChar === 'S'
                        && $lowerLeftChar === 'M' && $lowerRightChar === 'M') {
                        $result++;
                    }

                    if ($upperLeftChar === 'M' && $upperRightChar === 'M'
                        && $lowerLeftChar === 'S' && $lowerRightChar === 'S') {
                        $result++;
                    }
                }
            }
        }

        return $result;
    }

    private function searchWordByDirection(array $lines, int $row, int $col, int $rowDirection, int $colDirection, string $word): bool {
        $wordLength = strlen($word);
        for ($index = 0; $index < $wordLength; $index++) {
            if (!$this->searchInputByDirection($lines, $row, $col, $rowDirection, $colDirection, $index, $word)) {
                return false;
            }
        }

        return true;
    }

    private function searchInputByDirection(array $lines, int $row, int $col, int $rowDirection, int $colDirection, int $searchIndex, string $word): bool {
        $rowCount = count($lines);
        $colCount = strlen($lines[0]);

        $newRow = $row + $searchIndex * $rowDirection;
        $newCol = $col + $searchIndex * $colDirection;

        if ($newRow < 0 || $newRow >= $rowCount || $newCol < 0 || $newCol >= $colCount) {
            return false;
        }

        $searchWord = $word[$searchIndex] ?? '';
        $lineWord = $lines[$newRow][$newCol];

        return $lineWord === $searchWord;
    }

    private function searchAndCountWordInLinesTry1(array $lines): int {
        $result = 0;

        // Find all XMAS occurrences in the line
        foreach (SearchDirection::cases() as $searchDirection) {
            $newResult = $this->searchWordInAllDirections($lines, $searchDirection);
            $this->console->writeln('Search direction: ' . $searchDirection->name . ' - Search result: ' . $newResult);

            $result += $newResult;
        }

        $this->console->writeln('Search result: ' . $result );

        return $result;
    }

    private function searchWordInAllDirections(array $lines, SearchDirection $direction): int
    {
        $result = 0;
        $rowCount = count($lines);
        $colCount = strlen($lines[0]);

        if ($direction === SearchDirection::Right) {
            foreach ($lines as $line) {
                $index = 0;
                while ($index < $colCount) {
                    $char = $line[$index];
                    $index++;
                    if ($char === 'X') {
                        $char = $line[$index] ?? '';
                        if ($char === 'M') {
                            $index++;

                            $char = $line[$index] ?? '';
                            if ($char === 'A') {
                                $index++;

                                $char = $line[$index] ?? '';
                                if ($char === 'S') {
                                    $index++;
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($direction === SearchDirection::Left) {
            foreach ($lines as $line) {
                $index = 0;
                while ($index < $colCount) {
                    $char = $line[$index];
                    $index++;
                    if ($char === 'S') {
                        $char = $line[$index] ?? '';
                        if ($char === 'A') {
                            $index++;

                            $char = $line[$index] ?? '';
                            if ($char === 'M') {
                                $index++;

                                $char = $line[$index] ?? '';
                                if ($char === 'X') {
                                    $index++;
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($direction === SearchDirection::DownRight) {
            for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
                for ($colIndex = 0; $colIndex < $colCount; $colIndex++) {
                    $char = $lines[$rowIndex][$colIndex] ?? '';
                    if ($char === 'X') {
                        $char = $lines[$rowIndex + 1][$colIndex + 1] ?? '';
                        if ($char === 'M') {
                            $char = $lines[$rowIndex + 2][$colIndex + 2] ?? '';
                            if ($char === 'A') {
                                $char = $lines[$rowIndex + 3][$colIndex + 3] ?? '';
                                if ($char === 'S') {
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($direction === SearchDirection::DownLeft) {
            for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
                for ($colIndex = 0; $colIndex < $colCount; $colIndex++) {
                    $char = $lines[$rowIndex][$colIndex] ?? '';
                    if ($char === 'X') {
                        $char = $lines[$rowIndex + 1][$colIndex - 1] ?? '';
                        if ($char === 'M') {
                            $char = $lines[$rowIndex + 2][$colIndex - 2] ?? '';
                            if ($char === 'A') {
                                $char = $lines[$rowIndex + 3][$colIndex - 3] ?? '';
                                if ($char === 'S') {
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($direction === SearchDirection::UpRight) {
            for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
                for ($colIndex = 0; $colIndex < $colCount; $colIndex++) {
                    $char = $lines[$rowIndex][$colIndex] ?? '';
                    if ($char === 'X') {
                        $char = $lines[$rowIndex - 1][$colIndex + 1] ?? '';
                        if ($char === 'M') {
                            $char = $lines[$rowIndex - 2][$colIndex + 2] ?? '';
                            if ($char === 'A') {
                                $char = $lines[$rowIndex - 3][$colIndex + 3] ?? '';
                                if ($char === 'S') {
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($direction === SearchDirection::UpLeft) {
            for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
                for ($colIndex = 0; $colIndex < $colCount; $colIndex++) {
                    $char = $lines[$rowIndex][$colIndex] ?? '';
                    if ($char === 'X') {
                        $char = $lines[$rowIndex - 1][$colIndex - 1] ?? '';
                        if ($char === 'M') {
                            $char = $lines[$rowIndex - 2][$colIndex - 2] ?? '';
                            if ($char === 'A') {
                                $char = $lines[$rowIndex - 3][$colIndex - 3] ?? '';
                                if ($char === 'S') {
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($direction === SearchDirection::Up) {
            for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
                for ($colIndex = 0; $colIndex < $rowCount; $colIndex++) {
                    $char = $lines[$rowIndex][$colIndex] ?? '';
                    if ($char === 'X') {
                        $char = $lines[$rowIndex - 1][$colIndex] ?? '';
                        if ($char === 'M') {
                            $char = $lines[$rowIndex - 2][$colIndex] ?? '';
                            if ($char === 'A') {
                                $char = $lines[$rowIndex - 3][$colIndex] ?? '';
                                if ($char === 'S') {
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($direction === SearchDirection::Down) {
            for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
                for ($colIndex = 0; $colIndex < $rowCount; $colIndex++) {
                    $char = $lines[$rowIndex][$colIndex] ?? '';
                    if ($char === 'X') {
                        $char = $lines[$rowIndex + 1][$colIndex] ?? '';
                        if ($char === 'M') {
                            $char = $lines[$rowIndex + 2][$colIndex] ?? '';
                            if ($char === 'A') {
                                $char = $lines[$rowIndex + 3][$colIndex] ?? '';
                                if ($char === 'S') {
                                    $result++;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

}