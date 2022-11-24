<?php

namespace Naykel\Gotime\Casts;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DateCast implements CastsAttributes
{

    /**
     * Cast the dates to human readable dates
     * IMPORTANT must be a 'datetime' or 'timestamp'
     */
    public function get($model, $key, $value, $attributes)
    {

        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d-m-Y') : null;
    }

    /**
     * Converts to data back to database format
     */
    public function set($model, $key, $value, $attributes)
    {

        return $value ? Carbon::parse($value) : null;
    }
}
