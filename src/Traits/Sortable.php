<?php

namespace Naykel\Gotime\Traits;

trait Sortable
{
    /**
     * Get the sort direction for a given column.
     */
    public function getSortDirection(string $column): ?string
    {
        $sortColumn = $this->sortColumn ?? null;
        $sortDirection = $this->sortDirection ?? null;

        return $sortColumn === $column ? $sortDirection : null;
    }

    /**
     * Set the sort column and direction.
     */
    public function sortBy(string $column): void
    {
        $currentColumn = $this->sortColumn ?? null;
        $currentDirection = $this->sortDirection ?? 'asc';

        if ($currentColumn === $column) {
            $this->sortDirection = $currentDirection === 'asc' ? 'desc' : 'asc';
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
        if (! isset($this->sortColumn)) {
            return $query;
        }

        $sortDirection = $this->sortDirection ?? 'asc';

        return $query->orderBy($this->sortColumn, $sortDirection);
    }
}
