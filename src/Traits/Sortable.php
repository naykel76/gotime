<?php

namespace Naykel\Gotime\Traits;

trait Sortable
{
    public string $sortColumn = 'id';
    public string $sortDirection = 'asc';

    public function sortBy(string $column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumn = $column;
    }

    protected function applySorting($query)
    {
        return $query->orderBy($this->sortColumn, $this->sortDirection);
    }
}
