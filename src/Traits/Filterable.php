<?php

namespace Naykel\Gotime\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Naykel\Gotime\Enums\DateRange;

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
    public function setFilter(string $key, mixed $value, ?string $column = null): void
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

        // Store column info if provided for date_range filters
        if ($key === 'date_range' && $column) {
            $this->filters['date_range_column'] = $column;
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
                $this->filters[$key] = array_values(array_filter($this->filters[$key], fn($item) => $item != $value));

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
     * Loops through each filter in the $filters array and applies the appropriate WHERE clause:
     * - Special filters (like 'date_range') use enum-based custom handling
     * - Basic filters use standard WHERE or WHERE IN clauses based on value type
     * - Skips null and empty string values (but preserves 0 and false)
     */
    protected function applyFilters($query): Builder
    {
        foreach ($this->filters as $key => $value) {
            if ($key === 'date_range') {
                $column = $this->filters['date_range_column'] ?? 'created_at';
                $query = $this->applyDateRangeFilter($query, $value, $column);

                continue; // Skip to next filter - don't run basic filter logic below
            }

            // Skip the column storage key
            if ($key === 'date_range_column') {
                continue;
            }

            // Basic filter logic for all other filters (department, status, etc.)
            if ($value !== null && $value !== '') {
                if (is_array($value)) {
                    // Multi-value filter: WHERE column IN (value1, value2, ...)
                    $query->whereIn($key, $value);
                } else {
                    // Single value filter: WHERE column = value
                    $query->where($key, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Apply date range filtering to the given query builder.
     *
     * Uses DateRange enum to determine the appropriate date filtering logic.
     * Can be used independently or as part of applyFilters().
     */
    protected function applyDateRangeFilter($query, string $dateRangeValue, string $column = 'created_at'): Builder
    {
        $dateRange = DateRange::from($dateRangeValue);

        // add options to select desired date ranges?? For example: custom =
        // true/false or LastYear = true/false
        // - For the time being i will just disable the custom range but is is
        //   ok to leave the code here
        // -show hide date selector when custom is selected
        if ($dateRange === DateRange::Custom) {
            // Use custom date properties for custom ranges
            if (
                property_exists($this, 'customStartDate') && property_exists($this, 'customEndDate')
                && $this->customStartDate && $this->customEndDate
            ) {
                $query->whereBetween($column, [
                    Carbon::parse($this->customStartDate)->startOfDay(),
                    Carbon::parse($this->customEndDate)->endOfDay(),
                ]);
            }
        } else {
            // Handle preset ranges using enum
            $dates = $dateRange->dates();

            if ($dates) {
                $query->whereBetween($column, $dates);
            }
        }

        // If dates() returns null (e.g., 'all' time), no date filtering is applied
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
     * Get the human-readable label for an active filter key.
     *
     * This method is strictly for UI display purposes (e.g., showing active
     * filters). It does not affect filtering logic. Returns the configured
     * label from $filterOptions or the key itself if no label is set.
     */
    public function getActiveFilterLabel(string $key): string
    {
        return $this->filterOptions[$key]['label'] ?? $key;
    }

    /**
     * Get the human-readable display value for an active filter key/value pair.
     *
     * This method is strictly for UI display purposes (e.g., showing active
     * filters). It does not affect filtering logic. Returns the configured
     * display value from $filterOptions['displayValues'] or the value as a
     * string if no mapping is found.
     */
    public function getActiveFilterValue(string $key, mixed $value): string
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
