<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Array to store all active filters.
     *
     * Structure: ['column_name' => 'single_value'] or ['column_name' => ['value1', 'value2']].
     */
    public array $filters = [];

    /**
     * Set a filter value for a given key.
     *
     * - If filter mode is 'single' or not defined: always replaces the existing value (default behavior).
     * - If filter mode is 'multi': uses multi-value logic.
     */
    public function setFilter(string $key, mixed $value): void
    {
        // Clear filter if value is empty (but preserve 0 and false)
        if ($this->shouldClearFilter($value)) {
            $this->clearFilter($key);

            return;
        }

        $mode = $this->getFilterMode($key);

        if ($mode === 'single') {
            $this->setSingleFilter($key, $value);
        } else {
            $this->setMultiFilter($key, $value);
        }
    }

    /**
     * Clear a filter key or remove a specific value from a filter.
     *
     * - If no value is provided, the entire filter key is removed.
     * - If a value is provided and the filter is an array, the value is removed from the array.
     * - Single-value filters are not removed when attempting to remove a specific value.
     */
    public function clearFilter(string $key, mixed $value = null): void
    {
        if ($value === null) {
            // Remove entire filter key (clear all values).
            unset($this->filters[$key]);
        } else {
            // Remove specific value from array filter.
            if (is_array($this->filters[$key])) {
                // Filter out the specific value and re-index array.
                $this->filters[$key] = array_values(array_filter($this->filters[$key], fn ($item) => $item != $value));

                // Clean up: if array is now empty, remove the whole key.
                if (empty($this->filters[$key])) {
                    unset($this->filters[$key]);
                }
            }
        }
    }

    /**
     * Clear all active filters.
     *
     * Resets the filters array to an empty state. Uncomment `$this->resetPage()` if using pagination
     * to ensure the first page of results is displayed after clearing filters.
     */
    public function clearAllFilters(): void
    {
        $this->filters = [];
        // $this->resetPage(); // Uncomment if using pagination.
    }

    /**
     * Apply all active filters to the given query builder.
     *
     * Automatically detects single vs multi-value filters:
     * - Single values use WHERE clause.
     * - Arrays use WHERE IN clause.
     * - Skips null and empty string values (but allows 0 and false).
     */
    protected function applyFilters($query): Builder
    {
        foreach ($this->filters as $key => $value) {
            // Skip null and empty values (but allow 0 and false).
            if ($value !== null && $value !== '') {
                if (is_array($value)) {
                    // Multi-value filter: WHERE column IN (value1, value2, ...).
                    $query->whereIn($key, $value);
                } else {
                    // Single value filter: WHERE column = value.
                    $query->where($key, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Get the filter mode for a given key, defaulting to 'single' if not set.
     */
    public function getFilterMode(string $key): string
    {
        // Check for new filterOptions structure first
        if (property_exists($this, 'filterOptions') && isset($this->filterOptions[$key]['mode'])) {
            return $this->filterOptions[$key]['mode'];
        }

        return 'single';
    }

    /**
     * Get the display label for a filter key.
     *
     * Returns the configured label from filterOptions, or the key itself if no
     * label is set.
     */
    public function getFilterLabel(string $key): string
    {
        return $this->filterOptions[$key]['label'] ?? $key;
    }

    /**
     * Get the display value for a filter key/value pair.
     *
     * Looks up the display value from the filterOptions 'displayValues' array.
     * If no display value is found, returns the original value as a string.
     */
    public function getDisplayValue(string $key, mixed $value): string
    {
        return $this->filterOptions[$key]['displayValues'][$value] ?? (string) $value;
    }

    /**
     * Check if we should clear the filter based on the value
     */
    private function shouldClearFilter(mixed $value): bool
    {
        return empty($value) && $value !== 0 && $value !== false;
    }

    /**
     * Set a single-value filter (replaces existing value)
     */
    private function setSingleFilter(string $key, mixed $value): void
    {
        $this->filters[$key] = $value;
    }

    /**
     * Set a multi-value filter (adds to existing values)
     */
    private function setMultiFilter(string $key, mixed $value): void
    {
        // If the key doesn't exist yet, just set it
        if (! isset($this->filters[$key])) {
            $this->filters[$key] = $value;

            return;
        }

        $existing = $this->filters[$key];

        // If existing is already an array, add to it (avoid duplicates)
        if (is_array($existing)) {
            $this->addToArrayFilter($key, $value, $existing);
        } else {
            $this->convertToArrayFilter($key, $value, $existing);
        }
    }

    /**
     * Add value to an existing array filter
     */
    private function addToArrayFilter(string $key, mixed $value, array $existing): void
    {
        if (! in_array($value, $existing)) {
            $this->filters[$key][] = $value;
        }
    }

    /**
     * Convert single value to array and add new value
     */
    private function convertToArrayFilter(string $key, mixed $value, mixed $existing): void
    {
        if ($existing !== $value) {
            $this->filters[$key] = [$existing, $value];
        }
    }
}
