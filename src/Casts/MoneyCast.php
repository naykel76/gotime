<?php

namespace Naykel\Gotime\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    /**
     * Convert stored integer (cents) to a formatted dollar string.
     *
     * Example: 1999 → "19.99"
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value === null
            ? null
            : number_format($value / 100, 2, '.', '');
    }

    /**
     * Convert a dollar string or number to integer (cents) for storage.
     *
     * Example: "19.99" → 1999, null → null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Convert to float first to handle string inputs like "19.99"
        return (int) round((float) $value * 100);
    }
}
