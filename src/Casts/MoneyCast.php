<?php

namespace Naykel\Gotime\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value from cents to dollars
     */
    public function get($model, $key, $value, $attributes)
    {
        return number_format($value / 100, 2, '.', '');
    }

    /**
     * Converts dollars to cents of storage.
     */
    public function set($model, $key, $value, $attributes)
    {
        return $value * 100;
    }
}
