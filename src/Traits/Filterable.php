<?php

namespace Naykel\Gotime\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Filterable
 * 
 * This trait provides a set of reusable query scopes for filtering records.
 * It is intended to be used in Eloquent models.
 * 
 * @package Naykel\Gotime\Traits
 */
trait Filterable
{
    /**
     * Filter records where the given date column falls within the last N days.
     * 
     * Example usage:
     * Orders::whereDateWithinLast('created_at', 7)->get();
     * 
     * @param Builder $query The query builder instance.
     * @param string $column The date column to filter by.
     * @param int $days The number of days to look back.
     * @return Builder
     */
    public function scopeWhereDateWithinLast(Builder $query, string $column, int $days): Builder
    {
        return $query->where($column, '>=', Carbon::now()->subDays($days))
            ->whereNotNull($column);
    }
}
