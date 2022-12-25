<?php
namespace Technoyer\Larageo;

use Illuminate\Support\Str;
use Technoyer\Larageo\Exceptions\LarageoException;

class LarageoDriver
{
    public $driver, $driver_api_base, $driver_api_method, $service;
    private $driver_api_key;

    public function __construct(private array $config)
    {
        $this->driver = $this->config['driver'];
        
        $driver_class_name = Str::ucfirst($this->driver).'Driver';
        $class_name = '\Technoyer\Larageo\Drivers\\'.$driver_class_name;
        
        if( class_exists($class_name) )
        {
            $this->service = new $class_name($this->config);
            $this->driver_api_base = $this->service->driver_api_base;
            $this->driver_api_key = $this->service->getApiKey();
            $this->driver_api_method = $this->service->driver_api_method;
            
            return $this;
        } else {
            throw new LarageoException('Driver does not exists or not supported!');
        }
    }

    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}