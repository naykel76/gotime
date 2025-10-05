<?php

namespace Naykel\Gotime\Enums;

use Illuminate\Support\Carbon;

enum DateRange: string
{
    case AllTime = 'all';
    case Today = 'today';
    case Last7 = 'last7';
    case Last30 = 'last30';
    case ThisMonth = 'thisMonth';
    case LastMonth = 'lastMonth';
    case ThisYear = 'thisYear';
    case LastYear = 'lastYear';
    case Custom = 'custom';

    public function label(?string $start = null, ?string $end = null): string
    {
        return match ($this) {
            self::AllTime => 'All Time',
            self::Today => 'Today',
            self::Last7 => 'Last 7 Days',
            self::Last30 => 'Last 30 Days',
            self::ThisMonth => 'This Month',
            self::LastMonth => 'Last Month',
            self::ThisYear => 'This Year',
            self::LastYear => 'Last Year',
            self::Custom => ($start !== null && $end !== null)
                ? str($start)->replace('-', '/') . ' - ' . str($end)->replace('-', '/')
                : 'Custom Range',
        };
    }

    public function dates(): ?array
    {
        return match ($this) {
            self::AllTime => null,
            self::Today => [Carbon::today(), Carbon::today()->endOfDay()],
            self::Last7 => [Carbon::today()->subDays(6), now()],
            self::Last30 => [Carbon::today()->subDays(29), now()],
            self::ThisMonth => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            self::LastMonth => [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()],
            self::ThisYear => [Carbon::now()->startOfYear(), now()],
            self::LastYear => [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()],
            self::Custom => null,
        };
    }
}
