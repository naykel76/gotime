<?php

namespace Naykel\Gotime\Traits;

trait Searchable
{
    public string $search = '';

    /**
     * This function is called when the search is updated. It resets the
     * pagination to first page.
     */
    public function updatingSearch(): void
    {
        $this->gotoPage(1);
    }

    /**
     * Apply the search query to the query.
     */
    protected function applySearch($query)
    {
        if (!isset($this->searchableFields)) {
            throw new \Exception('You must define a `$searchableFields` array in the ' . get_class($this) . ' component');
        }

        if ($this->search === '') return $query;

        foreach ($this->searchableFields as $option) {
            $query = $query->orWhere($option, 'like', '%' . $this->search . '%');
        }

        return $query;
    }
}
