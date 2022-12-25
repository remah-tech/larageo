<?php
namespace Technoyer\Larageo\Drivers;

class LarageoDriversBase
{
    public $driver;
    public $driver_api_base;
    public $driver_api_method = 'GET';
    private $driver_api_key;
}