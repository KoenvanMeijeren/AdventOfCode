<?php

namespace  App\console\Y24\day11;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

ini_set('memory_limit', '1G');

/**
 * Provides the ChallengeCommand.
 */
final readonly class ChallengeCommand {

    public function __construct(
        private Console $console,
    ) {}

    #[ConsoleCommand(
        name: 'aoc:2024:day11',
        description: 'Runs the Day 11 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 11 of 2024...');

        $testInput = file_get_contents(__DIR__ . '/test-input.txt');
        $input = file_get_contents(__DIR__ . '/input.txt');

        $this->console->writeln('Result test: ' .  implode(' ', $this->blink($testInput)[0]));
        [$result, $count] = $this->blink('125 17');
        $this->console->writeln('Count: ' . $count . ' Result initial arrangement: ' .  implode(' ', $result));
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 2 blinks: ' .  implode(' ', $result));
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 3 blinks: ' .  implode(' ', $result));
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 4 blinks: ' .  implode(' ', $result));
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 5 blinks: ' .  implode(' ', $result));
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 6 blinks: ' .  implode(' ', $result));

        $result = $input;
        $count = 0;
        $this->console->writeln();
        for ($i = 0; $i < 25; $i++) {
            $this->console->writeln('Blink ' . $i);
            [$result, $count] = $this->blink($result);
        }

        $this->console->writeln('Count: ' . $count . ' result after 25 blinks ');

        $result = $input;
        $count = 0;
        $this->console->writeln();
        $part1BlinksMax = 35;
        for ($i = 0; $i < $part1BlinksMax; $i++) {
            $this->console->writeln('Blink ' . $i);
            [$result, $count] = $this->blink($result);
        }

        $this->console->writeln('Count: ' . $count . ' result after ' . $part1BlinksMax . ' blinks ');

        $this->console->writeln();
        $this->console->writeln('Optimized version');
        $result = $this->blinkOptimized('125 17');
        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result initial arrangement: ' .  $result);
        $result = $this->blinkOptimized($result);
        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 2 blinks: ' .  $result);
        $result = $this->blinkOptimized($result);
        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 3 blinks: ' .  $result);
        $result = $this->blinkOptimized($result);
        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 4 blinks: ' .  $result);
        $result = $this->blinkOptimized($result);
        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 5 blinks: ' .  $result);
        $result = $this->blinkOptimized($result);
        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 6 blinks: ' .  $result);

        $result = $input;
        $this->console->writeln();
        $part2Blinks = 40;
        for ($i = 0; $i < $part2Blinks; $i++) {
            $this->console->writeln('Blink ' . $i);
            $result = $this->blinkOptimized($result);
        }

        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' result after ' . $part2Blinks . ' blinks ');
    }

    private function blink(string|array $input): array
    {
        $digitGroups = $input;
        if (is_string($digitGroups)) {
            $digitGroups = explode(" ", $input);
        }

        $result = [];
        foreach ($digitGroups as  $digitGroup) {
            $newResult = $this->blinkDigit((int) $digitGroup);
            foreach ($newResult as $newDigit) {
                $result[] = $newDigit;
            }
        }

        return [$result, count($result)];
    }

    private function blinkDigit(int $input): array
    {
        // If the stone is engraved with the number 0, it is replaced by a stone engraved with the number 1.
        if ($input === 0) {
            return [1];
        }

        // If the stone is engraved with a number that has an even number of digits, it is replaced by two stones.
        // The left half of the digits are engraved on the new left stone, and the right half of
        // the digits are engraved on the new right stone.
        // (The new numbers don't keep extra leading zeroes: 1000 would become stones 10 and 0.)
        $inputStr = (string) $input;
        $inputLength = strlen($inputStr);
        if ($inputLength % 2 === 0) {
            $left = substr($inputStr, 0, $inputLength / 2);
            $right = substr($inputStr, $inputLength / 2);
            return [(int) $left, (int) $right];
        }

        //If none of the other rules apply, the stone is replaced by a new stone;
        // the old stone's number multiplied by 2024 is engraved on the new stone.
        return [$input * 2024];
    }

    private function blinkOptimized(string $input): string
    {
        $result = '';
        $length = strlen($input);
        $index = 0;

        while ($index < $length) {
            $char = $input[$index];

            // If the current character is a space, append it and move to the next character.
            if ($char === ' ') {
                $result .= ' ';
                $index++;
                continue;
            }

            // If the character is a digit, process the entire number.
            if (ctype_digit($char)) {
                $startIndex = $index;

                // Move index forward to the next space or end of string.
                while ($index < $length && $input[$index] !== ' ') {
                    $index++;
                }

                // Extract the full number and process it.
                $digit = substr($input, $startIndex, $index - $startIndex);
                $result .= $this->blinkDigitOptimized((int) $digit);
                continue;
            }

            // If it's any other character, append it to the result.
            $result .= $char;
            $index++;
        }

        return $result;
    }

    private function blinkOptimizedRecursive(string $input, string $result = '', int $index = 0): string
    {
        // Base case: If we reach the end of the string, return the result.
        if ($index >= strlen($input)) {
            return $result;
        }

        // Current character
        $startIndex = $index;
        $char = $input[$startIndex];

        // If the current character is a space, add it to the result and continue recursion.
        if ($char === ' ') {
            return $this->blinkOptimizedRecursive($input, $result . ' ', $index + 1);
        }

        if (ctype_digit($char)) {
            // Find the next space and continue processing from there
            while ($index < strlen($input) && $input[$index] !== ' ') {
                $index++;
            }

            $digit = substr($input, $startIndex, $index - $startIndex);
            $newResult = $this->blinkDigitOptimized((int) $digit);

            return $this->blinkOptimizedRecursive($input, $result . $newResult, $index);
        }

        // Otherwise, append the character and continue.
        return $this->blinkOptimizedRecursive($input, $result . $char, $index + 1);
    }

    private function blinkDigitOptimized(int $input): string
    {
        // If the stone is engraved with the number 0, it is replaced by a stone engraved with the number 1.
        if ($input === 0) {
            return '1';
        }

        // If the stone is engraved with a number that has an even number of digits, it is replaced by two stones.
        // The left half of the digits are engraved on the new left stone, and the right half of
        // the digits are engraved on the new right stone.
        // (The new numbers don't keep extra leading zeroes: 1000 would become stones 10 and 0.)
        $inputStr = (string) $input;
        $inputLength = strlen($inputStr);
        if ($inputLength % 2 === 0) {
            $left = (int) substr($inputStr, 0, $inputLength / 2);
            $right = (int) substr($inputStr, $inputLength / 2);
            return $left . ' ' . $right;
        }

        //If none of the other rules apply, the stone is replaced by a new stone;
        // the old stone's number multiplied by 2024 is engraved on the new stone.
        return (string) $input * 2024;
    }

    private function countDigitGroups(string $string): int {
        $count = 0;        // Number of digit groups
        $inGroup = false;  // Flag to track if we're inside a digit group

        // Loop through each character in the string
        $strLength = strlen($string);
        for ($i = 0; $i < $strLength; $i++) {
            $char = $string[$i];

            if (ctype_digit($char)) {
                // If we're not already in a group, increment the count
                if (!$inGroup) {
                    $count++;
                    $inGroup = true; // Mark that we're now inside a group
                }
            } else {
                // If we hit a non-digit character, reset the group flag
                $inGroup = false;
            }
        }

        return $count;
    }

}