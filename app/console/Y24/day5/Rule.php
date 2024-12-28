<?php

namespace App\console\Y24\day5;

/**
 * Provides the Rule.
 */
final readonly class Rule implements \Stringable {

    public function __construct(
        public int $comparePageNr,
        public int $sortPageNr,
        public SortEnum $sortDirection,
    ) {}

    public static function fromString(string $input): self
    {
        [$sortPageNr, $comparePageNr] = explode('|', $input);
        $sortPageNr = (int) $sortPageNr;
        $comparePageNr = (int) $comparePageNr;

        $sortDirection = SortEnum::AFTER;
        if ($sortPageNr < $comparePageNr) {
            $sortDirection = SortEnum::BEFORE;
        }

        return new self($comparePageNr, $sortPageNr, $sortDirection);
    }

    public function __toString(): string
    {
        return "{$this->comparePageNr}|{$this->sortPageNr}|{$this->sortDirection->name}";
    }

}