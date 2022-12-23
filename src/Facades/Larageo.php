<?php
namespace Technoyer\Larageo\Facades;

use Illuminate\Support\Facades\Facade;

class Larageo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'larageo';
    }
}