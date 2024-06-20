<?php

namespace Naykel\Gotime\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given integer value (cents) to a float (dollars)
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        return round($value / 100, 2);
    }

    /**
     * Prepare the given float value (dollars) for storage as an integer (cents)
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        return empty($value) ? 0 : $value * 100;
    }
}
