<?php

namespace Naykel\Gotime\Enums;

enum OrderStatus: string
{
    case Paid = 'paid';
    case Pending = 'pending';
    case Failed = 'failed';
    case Refunded = 'refunded';

    public function label()
    {
        return match ($this) {
            static::Paid => 'Paid',
            static::Pending => 'Pending',
            static::Failed => 'Failed',
            static::Refunded => 'Refunded',
        };
    }

    public function icon()
    {
        return match ($this) {
            static::Paid => 'icon.check',
            static::Pending => 'icon.clock',
            static::Failed => 'icon.x-mark',
            static::Refunded => 'icon.arrow-uturn-left',
        };
    }

    public function color()
    {
        return match ($this) {
            static::Paid => 'green',
            static::Pending => 'dark',
            static::Failed => 'red',
            static::Refunded => 'purple',
        };
    }
}
