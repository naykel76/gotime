<?php

namespace Naykel\Gotime\Enums;

enum PublishedStatus: string
{
    case Released = 'released';
    case Published = 'published';
    case Draft = 'draft';

    public function label()
    {
        return match ($this) {
            self::Released => 'Released',
            self::Published => 'Published',
            self::Draft => 'Draft',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::Released => 'check',
            self::Published => 'book-open',
            self::Draft => 'pencil',
        };
    }

    public function color()
    {
        return match ($this) {
            self::Released => 'bg-orange-100',
            self::Published => 'bg-green-100',
            self::Draft => 'bg-orange-100',
        };
    }

    public function colorText()
    {
        return match ($this) {
            self::Released => 'txt-orange-700',
            self::Published => 'txt-green-700',
            self::Draft => 'txt-orange-700',
        };
    }
}
