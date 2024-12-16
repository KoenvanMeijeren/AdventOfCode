<?php

namespace  App\console\Y24\day11;

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
        name: 'aoc:2024:day11',
        description: 'Runs the Day 11 challenge of 2024.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 11 of 2024...');

        $testInput = file_get_contents(__DIR__ . '/test-input.txt');
        $input = file_get_contents(__DIR__ . '/input.txt');

        $this->console->writeln('Result test: ' .  $this->blink($testInput)[0]);
        [$result, $count] = $this->blink('125 17');
        $this->console->writeln('Count: ' . $count . ' Result initial arrangement: ' .  $result);
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 2 blinks: ' .  $result);
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 3 blinks: ' .  $result);
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 4 blinks: ' .  $result);
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 5 blinks: ' .  $result);
        [$result, $count] = $this->blink($result);
        $this->console->writeln('Count: ' . $count . ' Result after 6 blinks: ' .  $result);

        $result = $input;
        $count = 0;
        $this->console->writeln();
        for ($i = 0; $i < 25; $i++) {
            $this->console->writeln('Blink ' . $i);
            [$result, $count] = $this->blink($result);
        }

        $this->console->writeln('Count: ' . $count . ' result after 25 blinks ');
    }

    private function blink(string $input): array
    {
        $digitGroups = explode(" ", $input);
        $result = [];
        foreach ($digitGroups as  $digitGroup) {
            $newResult = $this->blinkDigit((int) $digitGroup);
            $result = array_merge($newResult, $result);
        }

        $result = array_reverse($result);
        return [implode(" ", $result), count($result)];
    }

    private function blinkDigit(int $input): array
    {
        // If the stone is engraved with the number 0, it is replaced by a stone engraved with the number 1.
        if ($input === 0) {
            return [1];
        }

        //If the stone is engraved with a number that has an even number of digits, it is replaced by two stones.
        // The left half of the digits are engraved on the new left stone, and the right half of
        // the digits are engraved on the new right stone.
        // (The new numbers don't keep extra leading zeroes: 1000 would become stones 10 and 0.)
        $inputStr = (string) $input;
        $inputLength = strlen($inputStr);
        if ($inputLength % 2 === 0) {
            $left = substr($inputStr, 0, $inputLength / 2);
            $right = substr($inputStr, $inputLength / 2);
            return [(int) $right, (int) $left];
        }

        //If none of the other rules apply, the stone is replaced by a new stone;
        // the old stone's number multiplied by 2024 is engraved on the new stone.
        return [$input * 2024];
    }
}