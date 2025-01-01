<?php

namespace App\shared\game\grid;

/**
 * Provides the Grid.
 */
final class Grid implements IGrid {

    /**
     * @param array<int, array<int, IGridTile>> $grid
     */
    public function __construct(
        public readonly int $rows,
        public readonly int $cols,
        public array $grid = []
    ) {}

    public static function fromArray(array $grid): self
    {
        $firstGridRow = reset($grid);
        $rows = count($grid);
        $cols = count($firstGridRow);

        return new self($rows, $cols, $grid);
    }

    public function getTile(int $row, int $col): ?IGridTile
    {
        return $this->grid[$row][$col] ?? null;
    }

    public function setTile(int $row, int $col, IGridTile $tile): void
    {
        $this->grid[$row][$col] = $tile;
    }

    public function isOutOfBounds(int $row, int $col): bool
    {
        return $this->isRowOutOfBounds($row) || $this->isColOutOfBounds($col);
    }

    public function isRowOutOfBounds(int $row): bool
    {
        return $row < 0 || $row >= $this->rows;
    }

    public function isColOutOfBounds(int $col): bool
    {
        return $col < 0 || $col >= $this->cols;
    }

    public function render(): string
    {
        $result = '';
        foreach ($this->grid as $row) {
            foreach ($row as $tile) {
                $result .= $tile->render();
            }
            $result .= "\n";
        }

        return $result;
    }

}