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

    public function backgroundColor()
    {
        return match ($this) {
            self::Released => 'bg-purple-100',
            self::Published => 'bg-green-100',
            self::Draft => 'bg-slate-100',
        };
    }

    public function textColor()
    {
        return match ($this) {
            self::Released => 'txt-purple-600',
            self::Published => 'txt-green-600',
            self::Draft => 'txt-slate-600',
        };
    }

    public function borderColor()
    {
        return match ($this) {
            self::Released => 'bdr-purple-200',
            self::Published => 'bdr-green-200',
            self::Draft => 'bdr-slate-200',
        };
    }
}
