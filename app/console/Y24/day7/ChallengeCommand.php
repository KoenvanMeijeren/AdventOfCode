<?php

namespace  App\console\Y24\day7;

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
        name: 'aoc:2024:day7',
        description: 'Runs the Day 7 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 7 of 2024...');

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        $result = 0;
        $correctEquations = 0;
        foreach ($lines as $line) {
            $this->console->writeln();
            $this->console->writeln($line);

            [$expectedResult, $numbers] = explode(': ', $line);
            $newResult = $this->calculateNumbersUntilItMatchesResult((int) $expectedResult, explode(' ', $numbers));
            if ($newResult > 0) {
                $correctEquations++;
                $result += $newResult;
            }
        }

        $this->console->writeln();
        $this->console->writeln(sprintf('Number of equations: %d', count($lines)));
        $this->console->writeln(sprintf('Number of correct equations: %d', $correctEquations));
        $this->console->writeln(sprintf('Number of equations that could possibly be true: %d', $result));
    }

    private function calculateNumbersUntilItMatchesResult(int $expectedResult, array $numbers): int
    {
        $calculatedResult = $this->calculateNumbersToResult($expectedResult, 0, $numbers);

        $this->console->writeln(sprintf('Expected: %d, Calculated: %d', $expectedResult, $calculatedResult));
        if ($calculatedResult === $expectedResult) {
            return $calculatedResult;
        }

        return 0;
    }

    private function calculateNumbersToResult(int $expectedResult, int $equationResult, array $remainingNumbers): int
    {
        // Base case.
        $numbersCount = count($remainingNumbers);
        if ($numbersCount === 0) {
            return $equationResult;
        }

        // Extract the first next number.
        $nextNumber = array_shift($remainingNumbers);
        if (is_numeric($nextNumber)) {
            $nextNumber = (int) $nextNumber;
        }

        // Try to add the numbers.
        $sum = $this->calculateNumbersToResult($expectedResult, $nextNumber + $equationResult, $remainingNumbers);
        if ($sum === $expectedResult) {
            return $sum;
        }

        // Try to multiply the numbers.
        $product = $this->calculateNumbersToResult($expectedResult, $nextNumber * $equationResult, $remainingNumbers);
        if ($product === $expectedResult) {
            return $product;
        }

        // Concatenate the numbers for part 2.
        // Return -1 for part 1.
        $concatenatedNumber = (int) "{$equationResult}{$nextNumber}";
        return $this->calculateNumbersToResult($expectedResult, $concatenatedNumber, $remainingNumbers);
    }

}