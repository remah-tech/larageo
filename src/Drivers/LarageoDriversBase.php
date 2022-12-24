<?php
namespace Technoyer\Larageo\Drivers;

class LarageoDriversBase
{
    public $driver;
    public $driver_api_base;
    public $driver_api_method = 'GET';

    public function getApiKey()
    {
        $api_key = null;
        if( !is_null($api_key = config( sprintf('larageo.drivers.%s.key', $this->driver))) )
        {
            return $api_key;
        }
    }
}