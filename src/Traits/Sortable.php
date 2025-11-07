<?php

namespace Naykel\Gotime\Traits;

trait Sortable
{
    public string $sortColumn = 'id';
    public string $sortDirection = 'asc';

    /**
     * Get the sort direction for a given column.
     */
    public function getSortDirection(string $column): ?string
    {
        return $this->sortColumn === $column ? $this->sortDirection : null;
    }

    /**
     * Set the sort column and direction.
     */
    public function sortBy(string $column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumn = $column;
    }

    /**
     * Apply the sorting to a query.
     */
    protected function applySorting($query)
    {
        return $query->orderBy($this->sortColumn, $this->sortDirection);
    }
}
