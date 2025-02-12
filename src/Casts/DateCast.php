<?php

namespace Naykel\Gotime\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DateCast implements CastsAttributes
{
    /**
     * Cast the dates to human readable dates
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value ? Carbon::parse($value)->format(config('gotime.date_format')) : null;
    }

    /**
     * Converts to data back to database format
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }
}
