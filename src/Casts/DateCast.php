<?php

namespace Naykel\Gotime\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DateCast implements CastsAttributes
{

    /**
     * Cast the dates to human readable dates
     * IMPORTANT must be a 'datetime' or 'timestamp'
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        // 16-02-2022
        // return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d-m-y') : null;
        // Feb 16, 2022
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('M d, Y') : null;
    }

    /**
     * Converts to data back to database format
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }
}
