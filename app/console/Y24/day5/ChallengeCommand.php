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

        $input = file_get_contents(__DIR__ . '/input.txt');
        $lines = explode("\n", $input);

        $this->ownTry($lines);
    }

    private function ownTry(array $lines): void {
        $result = 0;
        $resultPart2 = 0;

        $lineRules = [];
        $linePages = [];
        foreach ($lines as $line) {
            if (str_contains($line, '|')) {
                $lineRules[] = explode('|', $line);
                continue;
            }

            if (str_contains($line, ',')) {
                $linePages[] = explode(',', $line);
                continue;
            }
        }

        $this->console->writeln();
        $resultPages = [];
        $result2Pages = [];
        foreach ($linePages as $pages) {
            $resultPages[] = $this->getCorrectlySortedPages($pages, $lineRules);
            $result2Pages[] = $this->getIncorrectlySortedPages($pages, $lineRules);
        }

        $result2PagesSorted = [];
        $result2Pages = array_filter($result2Pages, fn ($page) => !empty($page));
        foreach ($result2Pages as $pages) {
            $result2PagesSorted[] = $this->sortPagesBasedOnRule($pages, $lineRules);
        }

        ray($result2PagesSorted, $lineRules);

        $resultPages = array_filter($resultPages, fn ($page) => !empty($page));
        foreach ($resultPages as $pages) {
            $pagesCount = count($pages);
            $middleIndex = round($pagesCount / 2) - 1;
            $result += $pages[$middleIndex];
        }

        foreach ($result2PagesSorted as $pages) {
            $pagesCount = count($pages);
            $middleIndex = round($pagesCount / 2) - 1;
            $resultPart2 += $pages[$middleIndex];
        }

        $this->console->writeln();
        $this->console->writeln('Result part 1: ' . $result);
        $this->console->writeln('Result part 2: ' . $resultPart2);
    }

    private function getCorrectlySortedPages(array $pages, array $rules): array {
        $isInOrder = $this->arePagesCorrectlySorted($pages, $rules);
        return $isInOrder ? $pages : [];
    }

    private function getIncorrectlySortedPages(array $pages, array $rules): array {
        $isInOrder = $this->arePagesCorrectlySorted($pages, $rules);
        return $isInOrder ? [] : $pages;
    }

    private function arePagesCorrectlySorted(array $pages, array $rules): bool {
        foreach ($rules as $rule) {
            [$comparePageNr, $sortPageNr] = $rule;

            if (!in_array($comparePageNr, $pages) || !in_array($sortPageNr, $pages)) {
                continue;
            }

            $beforeAfter = 'before';
            if ($comparePageNr > $sortPageNr) {
                $beforeAfter = 'after';
            }

            $comparePageIndex = array_search($comparePageNr, $pages, true);
            $sortPageIndex = array_search($sortPageNr, $pages, true);

            // Add the pages to the result if the compare page is before the sort page and the compare page index is
            // lower than the sort page index.
            if ($beforeAfter === 'before' && $comparePageIndex > $sortPageIndex) {
                return false;
            }

            // Add the pages to the result if the compare page is after the sort page and the compare page index is
            // higher than the sort page index.
            if ($beforeAfter === 'after' && $sortPageIndex < $comparePageIndex) {
                return false;
            }
        }

        return true;
    }

    private function sortPagesBasedOnRule(array $pages, array $rules): array {
        $result = $pages;
        foreach ($rules as $rule) {
            [$comparePageNr, $sortPageNr] = $rule;

            if (!in_array($comparePageNr, $pages) || !in_array($sortPageNr, $pages)) {
                continue;
            }

            $beforeAfter = 'before';
            if ($comparePageNr > $sortPageNr) {
                $beforeAfter = 'after';
            }

            $comparePageIndex = array_search($comparePageNr, $pages, true);
            $sortPageIndex = array_search($sortPageNr, $pages, true);

            // Add the pages to the result if the compare page is before the sort page and the compare page index is
            // lower than the sort page index.
            if ($beforeAfter === 'before' && $comparePageIndex > $sortPageIndex) {
                // swap
                $temp = $result[$comparePageIndex];
                $result[$comparePageIndex] = $result[$sortPageIndex];
                $result[$sortPageIndex] = $temp;
            }

            // Add the pages to the result if the compare page is after the sort page and the compare page index is
            // higher than the sort page index.
            if ($beforeAfter === 'after' && $sortPageIndex < $comparePageIndex) {
                // swap
                $temp = $result[$comparePageIndex];
                $result[$comparePageIndex] = $result[$sortPageIndex];
                $result[$sortPageIndex] = $temp;
            }
        }

        return $result;
    }

}