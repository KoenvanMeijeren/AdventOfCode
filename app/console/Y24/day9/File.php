<?php

namespace App\console\Y24\day9;

final readonly class File implements \Stringable
{
    public function __construct(
        public int $id,
    ) {}

    public static function fromString(string $id): self
    {
        return new self((int) $id);
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}