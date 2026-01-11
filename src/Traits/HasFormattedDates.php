<?php

namespace Naykel\Gotime\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasFormattedDates
{
    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    |
    | Common date-based query scopes for filtering records.
    |
    */

    /**
     * Filter records where the given date column falls within the last N days.
     *
     * Example: User::whereDateWithinLast('created_at', 7)->get()
     */
    public function scopeWhereDateWithinLast(Builder $query, string $column, int $days): Builder
    {
        return $query->where($column, '>=', Carbon::now()->subDays($days))
            ->whereNotNull($column);
    }

    /**
     * Filter records where the given date column falls within the next N days.
     *
     * Example: Event::whereDateWithinNext('start_date', 30)->get()
     */
    public function scopeWhereDateWithinNext(Builder $query, string $column, int $days): Builder
    {
        return $query->where($column, '<=', Carbon::now()->addDays($days))
            ->where($column, '>=', Carbon::now())
            ->whereNotNull($column);
    }

    /**
     * Filter records where the given date column falls between two dates.
     *
     * Example: Order::whereDateBetween('created_at', '2024-01-01', '2024-12-31')->get()
     */
    public function scopeWhereDateBetween(Builder $query, string $column, string $start, string $end): Builder
    {
        return $query->whereBetween($column, [
            Carbon::parse($start)->startOfDay(),
            Carbon::parse($end)->endOfDay(),
        ]);
    }

    /**
     * Filter records where the given date column is today.
     *
     * Example: Task::whereDateToday('due_date')->get()
     */
    public function scopeWhereDateToday(Builder $query, string $column): Builder
    {
        return $query->whereDate($column, Carbon::today())
            ->whereNotNull($column);
    }

    /**
     * Filter records where the given date column is this week.
     *
     * Example: Appointment::whereDateThisWeek('scheduled_at')->get()
     */
    public function scopeWhereDateThisWeek(Builder $query, string $column): Builder
    {
        return $query->whereBetween($column, [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    /**
     * Filter records where the given date column is this month.
     *
     * Example: Sale::whereDateThisMonth('completed_at')->get()
     */
    public function scopeWhereDateThisMonth(Builder $query, string $column): Builder
    {
        return $query->whereBetween($column, [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ]);
    }

    /**
     * Filter records where the given date column is overdue (past today).
     *
     * Example: Task::whereDateOverdue('due_date')->get()
     */
    public function scopeWhereDateOverdue(Builder $query, string $column): Builder
    {
        return $query->where($column, '<', Carbon::now()->startOfDay())
            ->whereNotNull($column);
    }

    /*
    |--------------------------------------------------------------------------
    | Date Formatting
    |--------------------------------------------------------------------------
    |
    | Methods for formatting date attributes into human-readable strings.
    |
    */

    /**
     * Get the default date format from config or fallback.
     */
    protected function getDefaultDateFormat(): string
    {
        return config('gotime.date_format', 'Y-m-d');
    }

    /**
     * Format a date attribute using the specified or default format.
     */
    public function formAt(string $attribute, ?string $format = null): ?string
    {
        $format = $format ?? $this->getDefaultDateFormat();

        return $this->{$attribute}?->format($format);
    }

    /*
    |--------------------------------------------------------------------------
    | Quality of life methods for common date fields
    |--------------------------------------------------------------------------
    |
    | These methods provide formatted date strings for common date attributes.
    |
    */

    public function createdAt(?string $format = null): ?string
    {
        return $this->formAt('created_at', $format);
    }

    public function updatedAt(?string $format = null): ?string
    {
        return $this->formAt('updated_at', $format);
    }

    public function startedAt(?string $format = null): ?string
    {
        return $this->formAt('started_at', $format);
    }

    public function completedAt(?string $format = null): ?string
    {
        return $this->formAt('completed_at', $format);
    }

    public function expiredAt(?string $format = null): ?string
    {
        return $this->formAt('expired_at', $format);
    }

    public function publishedAt(?string $format = null): ?string
    {
        return $this->formAt('published_at', $format);
    }

    // other variations
    public function startDate(?string $format = null): ?string
    {
        return $this->formAt('start_date', $format);
    }

    public function endDate(?string $format = null): ?string
    {
        return $this->formAt('end_date', $format);
    }

    public function expiryDate(?string $format = null): ?string
    {
        return $this->formAt('expiry_date', $format);
    }
}
