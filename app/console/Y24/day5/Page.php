<?php

namespace App\console\Y24\day5;

/**
 * Provides the Page.
 */
final readonly class Page implements \Stringable {

    public function __construct(
        public int $pageNumber,
    ) {}

    public static function fromString(string $input): self
    {
        return new self(
            pageNumber: (int) $input,
        );
    }

    /**
     * @param array<self> $pages
     * @param array<Rule> $rules
     */
    public function isCorrectlySorted(array $pages, array $rules): bool
    {
        $pageNumbers = array_map(static fn (Page $page) => $page->pageNumber, $pages);
        $pageNumbers = array_values($pageNumbers);

        foreach ($rules as $rule) {
            if (!in_array($rule->comparePageNr, $pageNumbers, true)
                || !in_array($rule->sortPageNr, $pageNumbers, true)) {
                continue;
            }

            $comparePageIndex = array_search($rule->comparePageNr, $pageNumbers, true);
            $sortPageIndex = array_search($rule->sortPageNr, $pageNumbers, true);

            // Flag the pages as not correctly sorted if the compare page is after
            // the sort page and the compare page index is higher than the sort page.
            if ($rule->sortDirection === SortEnum::BEFORE
                && $sortPageIndex > $comparePageIndex) {
                return false;
            }

            // Flag the pages as not correctly sorted if the compare page is before
            // the sort page and the compare page index is lower than the sort page.
            if ($rule->sortDirection === SortEnum::AFTER
                && $comparePageIndex < $sortPageIndex) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return (string) $this->pageNumber;
    }

}