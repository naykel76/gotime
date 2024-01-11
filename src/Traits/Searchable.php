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

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query
            ->where('number', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%');
    }
}
