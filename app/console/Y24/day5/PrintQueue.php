<?php

namespace App\console\Y24\day5;

/**
 * Provides the PrintQueue.
 */
final readonly class PrintQueue implements \Stringable {

    /**
     * @param array<Book> $books
     * @param array<Rule> $rules
     */
    public function __construct(
        public array $books,
        public array $rules,
    ) {}

    public static function fromLines(array $lines): self
    {
        $rules = [];
        $books = [];
        foreach ($lines as $line) {
            if (str_contains($line, '|')) {
                $rules[] = Rule::fromString($line);
            }

            if (str_contains($line, ',')) {
                $books[] = Book::fromString($line);
            }
        }

        return new self($books, $rules);
    }

    /**
     * @return array<Book>
     */
    public function getCorrectlySortedBooks(): array
    {
        $result = [];
        foreach ($this->books as $book) {
            if ($book->isCorrectlySorted($this->rules)) {
                $result[] = $book;
            }
        }

        return $result;
    }

    /**
     * @return array<Book>
     */
    public function getIncorrectlySortedBooks(): array
    {
        $result = [];
        foreach ($this->books as $book) {
            if (!$book->isCorrectlySorted($this->rules)) {
                $result[] = $book;
            }
        }

        return $result;
    }

    public function sortIncorrectlySortedBooks(): array
    {
        $result = [];
        $books = $this->getIncorrectlySortedBooks();
        foreach ($books as $book) {
            $book = clone $book;

            // Sort the book until it is correctly sorted.
            while (!$book->isCorrectlySorted($this->rules)) {
                $book->sort($this->rules);
            }

            $result[] = $book;
        }

        return $result;
    }

    /**
     * @param array<Book> $books
     */
    public function getPuzzleAnswer(array $books): int
    {
        $result = 0;
        foreach ($books as $book) {
            if (!$book->isCorrectlySorted($this->rules)) {
                throw new \RuntimeException('Book is not correctly sorted.');
            }

            $result += $book->getMiddlePage()->pageNumber;
        }

        return $result;
    }

    public function __toString(): string
    {
        return implode("\n", $this->books);
    }

}