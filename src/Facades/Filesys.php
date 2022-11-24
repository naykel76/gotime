<?php

namespace Naykel\Gotime\Facades;

use Illuminate\Support\Facades\Facade;

class Filesys extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filesys';
    }
}
