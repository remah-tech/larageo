<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

final class IpapicoDriver extends LarageoDriversBase
{
    private $driver_api_key;

    public function __construct(private array $config)
    {
        $this->driver = "ipapico";
        $this->driver_api_base = "https://ipapi.co/%ip%/json/";
        $this->driver_api_method = $this->config['drivers'][$this->driver]['method'] ?? 'GET';
        $this->driver_api_key = $this->config['drivers'][$this->driver]['key'] ?? null;
        
        //Detrmine Keys of Response
        $this->country = 'country_name';
        $this->country_code = 'country_code';
        $this->city = 'city';
        $this->region = 'region';
        $this->latitude = 'latitude';
        $this->longitude = 'longitude';
        $this->languages = 'languages';
        $this->timezone = 'timezone';
        $this->zipcode = 'postal';
        $this->isp = 'hostname';
    }
    
    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}