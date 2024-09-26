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
            self::Paid => 'Paid',
            self::Pending => 'Pending',
            self::Failed => 'Failed',
            self::Refunded => 'Refunded',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::Paid => 'icon.check',
            self::Pending => 'icon.clock',
            self::Failed => 'icon.x-mark',
            self::Refunded => 'icon.arrow-uturn-left',
        };
    }

    public function color()
    {
        return match ($this) {
            self::Paid => 'green',
            self::Pending => 'dark',
            self::Failed => 'red',
            self::Refunded => 'purple',
        };
    }
}
