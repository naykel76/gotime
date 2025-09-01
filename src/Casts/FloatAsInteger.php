<?php

namespace Naykel\Gotime\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class FloatAsInteger implements CastsAttributes
{
    public function __construct(private int $decimals = 2) {}

    /**
     * Convert the stored integer value back to a float for the application.
     *
     * Takes the integer value from the database and divides by 10^decimals
     * to restore the original decimal places.
     *
     * @example
     * // With 2 decimals: 1999 → 19.99
     * // With 3 decimals: 1925 → 1.925
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        // Convert integer back to float: divide by 10^decimals
        // round() ensures we get exactly the right number of decimal places
        return $value === null ? null : round($value / (10 ** $this->decimals), $this->decimals);
    }

    /**
     * Convert the float value to an integer for database storage.
     *
     * Takes the float value from the application and multiplies by 10^decimals
     * to shift decimal places, then stores as an integer.
     *
     * @example
     * // With 2 decimals: 19.99 → 1999
     * // With 3 decimals: 1.925 → 1925
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        // Convert float to integer: multiply by 10^decimals and round
        // Cast to int to ensure we store an integer type
        return $value === null ? null : (int) round($value * (10 ** $this->decimals));
    }
}
