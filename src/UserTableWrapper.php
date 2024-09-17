<?php

namespace App;

use App\TableWrapperInterface;

class UserTableWrapper implements TableWrapperInterface
{
    private array $rows = [];

    public function insert(array $values): void
    {
        $this->rows[] = $values;
    }

    public function update(int $id, array $values): array
    {
        foreach ($this->rows as &$row) {
            if (isset($row['id']) && $row['id'] === $id) {
                $row = array_merge($row, $values);
                return $row;
            }
        }

        return [];
    }

    public function delete(int $id): void
    {
        foreach ($this->rows as $index => $row) {
            if (isset($row['id']) && $row['id'] === $id) {
                unset($this->rows[$index]);
                break;
            }
        }

        $this->rows = array_values($this->rows);
    }

    public function get(): array
    {
        return $this->rows;
    }
}