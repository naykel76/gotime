<?php

namespace Naykel\Gotime\Facades;

use Illuminate\Support\Facades\Facade;

class RouteBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'routebuilder';
    }
}
