<?php

namespace  App\console\Y24\day11;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

/**
 * Provides the ChallengeCommand.
 */
final class ChallengeCommand {

    public function __construct(
        private readonly Console $console,
        private array $cache = []
    ) {}

    #[ConsoleCommand(
        name: 'aoc:2024:day11',
        description: 'Runs the Day 11 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 11 of 2024...');

        $testInputContent = file_get_contents(__DIR__ . '/test-input.txt');
        $testInput = $this->inputToArray($testInputContent);
        $anotherTestInputContent = '125 17';
        $anotherTestInput = $this->inputToArray($anotherTestInputContent);
        $inputContent = file_get_contents(__DIR__ . '/input.txt');
        $input = $this->inputToArray($inputContent);

        $this->console->writeln('Result test: ' .  implode(' ', $this->blink($testInput)[0]));
        [$result, $count] = $this->blink($anotherTestInput);
        $this->console->writeln('Count: ' . $count . ' Result initial arrangement: ' .  implode(' ', $result));
        [$result, $count] = $this->blink($result);
        for ($i = 0; $i < 8; $i++) {
            $this->console->writeln('Count: ' . $count . ' Result after ' . $i . ' blinks: ' .  implode(' ', $result));
            [$result, $count] = $this->blink($result);
        }

        $this->console->writeln();
        $this->console->writeln('Optimized version - LanternFish solution');

        // First blink for the another test input.
        $this->console->writeln();
        $this->console->writeln('Blink 6 times for the another test input');
        $anotherTestInput = $this->inputToLanternFishArray($anotherTestInput);
        $this->console->writeln('Count 0 after 0 blinks. Input: ' . implode(' ', array_keys($anotherTestInput)));
        $result = $this->blinkWithLanternFishSolution($anotherTestInput);
        $count = array_sum($result);
        $this->console->writeln('Count ' . $count . ' after ' . 1 . ' blinks. Input: ' . implode(' ', array_keys($result)));

        // Next X blinks for the another test input.
        for ($i = 1; $i < 6; $i++) {
            $result = $this->blinkWithLanternFishSolution($result);
            $count = array_sum($result);
            $this->console->writeln('Count ' . $count . ' after ' . $i + 1 . ' blinks. Input: ' . implode(' ', array_keys($result)));
        }

        // Blink 25 times for the input.
        $this->console->writeln();
        $this->console->writeln('Blink 25 times for the input.');
        $input = $this->inputToLanternFishArray($input);
        $this->console->writeln('Count 0 after 0 blinks. Input: ' . implode(' ', array_keys($input)));
        for ($i = 0; $i < 25; $i++) {
            $input = $this->blinkWithLanternFishSolution($input);
            $count = array_sum($input);
            $this->console->writeln('Count ' . $count . ' after ' . $i + 1 . ' blinks.');
        }

        // Blink 75 times for the input.
        $this->console->writeln();
        $this->console->writeln('Blink 75 times for the input.');
        $input = $this->inputToArray($inputContent);
        $input = $this->inputToLanternFishArray($input);
        $this->console->writeln('Count 0 after 0 blinks. Input: ' . implode(' ', array_keys($input)));
        for ($i = 0; $i < 75; $i++) {
            $input = $this->blinkWithLanternFishSolution($input);
            $count = array_sum($input);
            $this->console->writeln('Count ' . $count . ' after ' . $i + 1 . ' blinks.');
        }

//        $result = $input;
//        $count = 0;
//        $this->console->writeln();
//        for ($i = 0; $i < 25; $i++) {
//            $this->console->writeln('Blink ' . $i);
//            [$result, $count] = $this->blink($result);
//        }
//
//        $this->console->writeln('Count: ' . $count . ' result after 25 blinks ');

//        $result = $input;
//        $count = 0;
//        $this->console->writeln();
//        $part1BlinksMax = 35;
//        for ($i = 0; $i < $part1BlinksMax; $i++) {
//            $this->console->writeln('Blink ' . $i);
//            [$result, $count] = $this->blink($result);
//        }
//
//        $this->console->writeln('Count: ' . $count . ' result after ' . $part1BlinksMax . ' blinks ');
//
//        $this->console->writeln();
//        $this->console->writeln('Optimized version');
//        $result = $this->blinkOptimized('125 17');
//        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result initial arrangement: ' .  $result);
//        $result = $this->blinkOptimized($result);
//        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 2 blinks: ' .  $result);
//        $result = $this->blinkOptimized($result);
//        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 3 blinks: ' .  $result);
//        $result = $this->blinkOptimized($result);
//        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 4 blinks: ' .  $result);
//        $result = $this->blinkOptimized($result);
//        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 5 blinks: ' .  $result);
//        $result = $this->blinkOptimized($result);
//        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' Result after 6 blinks: ' .  $result);
//
//        $result = $input;
//        $this->console->writeln();
//        $this->console->writeln('Optimized version part 1 - With string manipulation');
//        $part2Blinks = 35;
//        for ($i = 0; $i < $part2Blinks; $i++) {
//            $this->console->writeln('Blink ' . $i);
//            $result = $this->blinkOptimized($result);
//        }
//
//        $this->console->writeln('Count: ' . $this->countDigitGroups($result) . ' result after ' . $part2Blinks . ' blinks ');

//        $this->console->writeln();
//        $this->console->writeln('Optimized version part 2 - With recursion');
//        $part3Blinks = 35;
//        $count = 0;
//        $result = $input;
//        for ($i = 0; $i < $part3Blinks; $i++) {
//            $this->console->writeln('Blink ' . $i);
//            $result = $this->blinkOptimizedRecursive($result);
//        }
//
//        $this->console->writeln('Count: ' . $count . ' result after ' . $part3Blinks . ' blinks ');
    }

    private function inputToArray(string $input): array
    {
        return explode(" ", $input);
    }

    private function inputToLanternFishArray(array $input): array
    {
        $result = [];
        foreach ($input as $item) {
            $result[$item] = 1;
        }

        return $result;
    }

    private function blink(array $digitGroups): array
    {
        $result = [];
        foreach ($digitGroups as  $digitGroup) {
            $newResult = $this->blinkDigit((int) $digitGroup);
            foreach ($newResult as $newDigit) {
                $result[] = $newDigit;
            }
        }

        return [$result, count($result)];
    }

    private function blinkWithLanternFishSolution(array $digitGroups): array
    {
        $result = [];
        foreach ($digitGroups as $digitGroup => $count) {
            $newResult = $this->blinkDigit((int) $digitGroup);
            foreach ($newResult as $newDigit) {
                // Increment the count for each new digit
                $result[$newDigit] ??= 0;
                $result[$newDigit] += $count;
            }
        }

        return $result;
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
        // Check if the result for the given input is already cached
        if (isset($this->cache[$input])) {
            return $this->cache[$input];
        }

        // Base case: If we reach the end of the string, return the result.
        if ($index >= strlen($input)) {
            // Cache the result before returning
            $this->cache[$input] = $result;
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
        $digits = (int) log10($input) + 1;
        if ($digits % 2 === 0) {
            $divisor = 10 ** ($digits / 2); // Calculate the divisor for splitting
            $left = (int) ($input / $divisor);
            $right = $input % $divisor;
            return $left . ' ' . $right;
        }

        // If none of the other rules apply, the stone is replaced by a new stone;
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