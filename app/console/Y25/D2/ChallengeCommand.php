<?php

namespace  App\console\Y25\D2;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

/**
 * Provides the Day1Command.
 */
final class ChallengeCommand {

    private int $solutionPart1 = 0;
    private int $solutionPart2 = 0;

    public function __construct(
        private readonly Console $console,
    ) {}

    #[ConsoleCommand(
        name: 'aoc:2025:day2',
        description: 'Runs the Day 2 challenge of 2025.',
    )]
    public function __invoke(): void
    {
        $this->console->writeln('Running AoC Day 2 of 2025...');

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        // Real results.
        $this->console->writeln();
        $this->console->writeln("Processing input...");
        foreach ($lines as $line) {
            $this->console->writeln($line);

            $idPairs = explode(',', $line);
            foreach ($idPairs as $idPair) {
                $ids = explode('-', $idPair);
                $start = (int) $ids[0];
                $end = (int) $ids[1];
                $invalidIdsCount = $this->getInvalidIdsCount($start, $end);
                $invalidIdsCount2 = $this->getInvalidIdsCountPart2($start, $end);

                $this->console->writeln("Start: {$start}, End: {$end}");
                $this->console->writeln("   -> Invalid ids count: {$invalidIdsCount}");
                $this->console->writeln("   -> Invalid ids count part 2: {$invalidIdsCount2}");
            }
        }

        $this->console->writeln();
        $this->console->writeln("Solution part 1: {$this->solutionPart1}");
        $this->console->writeln("Solution part 2: {$this->solutionPart2}");
    }

    private function getInvalidIdsCount(int $start, int $end): int
    {
        $result = 0;
        for ($i = $start; $i <= $end; $i++) {
            if ($this->isRepeatingId((string) $i)) {
                $result++;
                $this->solutionPart1 += $i;
            }
        }

        return $result;
    }

    private function getInvalidIdsCountPart2(int $start, int $end): int
    {
        $result = 0;
        for ($i = $start; $i <= $end; $i++) {
            $isRepeating = $this->isRepeatingId((string) $i);
            $isSequence = $this->isRepeatingSequenceId((string) $i);

            if ($isRepeating || $isSequence) {
                $result++;
                $this->solutionPart1 += $i;
                if ($isSequence) {
                    $this->solutionPart2 += $i;
                }
            }
        }

        return $result;
    }

    private function isRepeatingId(string $value): bool
    {
        $splitInHalf = str_split($value, (int) round(strlen($value) / 2));
        return ($splitInHalf[0] ?? null) === ($splitInHalf[1] ?? null);
    }

    private function isRepeatingSequenceId(string $value): bool
    {
        return preg_match('/^(.+)\1+$/', $value);
    }

}