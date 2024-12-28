<?php

namespace App\console\Y24\day5;

/**
 * Provides the Book.
 */
final class Book implements \Stringable {

    /**
     * @param array<Page> $pages
     */
    public function __construct(
        public array $pages,
    ) {}

    public static function fromString(string $input): self
    {
        $pageStrings = explode(',', $input);
        $pages = [];
        foreach ($pageStrings as $pageString) {
            $page = Page::fromString($pageString);
            $pages[$page->pageNumber] = $page;
        }

        return new self($pages);
    }

    /**
     * @param array<Rule> $rules
     */
    public function isCorrectlySorted(array $rules): bool
    {
        foreach ($this->pages as $page) {
            if (!$page->isCorrectlySorted($this->pages, $rules)) {
                return false;
            }
        }

        return true;
    }

    public function sort(array $rules): void
    {
        $pageNumbers = array_map(static fn (Page $page) => $page->pageNumber, $this->pages);
        $pageNumbers = array_values($pageNumbers);

        foreach ($rules as $rule) {
            if (!in_array($rule->comparePageNr, $pageNumbers, true)
                || !in_array($rule->sortPageNr, $pageNumbers, true)) {
                continue;
            }

            $comparePageIndex = array_search($rule->comparePageNr, $pageNumbers, true);
            $sortPageIndex = array_search($rule->sortPageNr, $pageNumbers, true);

            if ($rule->sortDirection === SortEnum::BEFORE
                && $sortPageIndex > $comparePageIndex) {
                $this->swapPages($pageNumbers, $comparePageIndex, $sortPageIndex);
            }

            if ($rule->sortDirection === SortEnum::AFTER
                && $comparePageIndex < $sortPageIndex) {
                $this->swapPages($pageNumbers, $comparePageIndex, $sortPageIndex);
            }
        }

        $this->pages = [];
        foreach ($pageNumbers as $pageNumber) {
            $this->pages[$pageNumber] = new Page($pageNumber);
        }
    }

    private function swapPages(array &$pages, int $comparePageIndex, int $sortPageIndex): void
    {
        $temp = $pages[$comparePageIndex];
        $pages[$comparePageIndex] = $pages[$sortPageIndex];
        $pages[$sortPageIndex] = $temp;
    }

    public function getMiddlePage(): Page
    {
        $pageNumbers = array_map(static fn (Page $page) => $page->pageNumber, $this->pages);
        $pageNumbers = array_values($pageNumbers);

        $middleIndex = (int) floor(count($pageNumbers) / 2);
        return $this->pages[$pageNumbers[$middleIndex]];
    }

    public function __toString(): string
    {
        return implode(',', $this->pages);
    }

}