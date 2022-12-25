<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

final class IpinfoDriver extends LarageoDriversBase
{
    private $driver_api_key;

    public function __construct(private array $config)
    {
        $this->driver = "ipinfo";
        $this->driver_api_base = "https://ipinfo.io/%ip%/json?token=%key%";
        $this->driver_api_method = $this->config['drivers'][$this->driver]['method'] ?? 'GET';
        $this->driver_api_key = $this->config['drivers'][$this->driver]['key'] ?? null;

        //Detrmine Keys of Response
        $this->country_code = 'country';
        $this->city = 'city';
        $this->region = 'region';
        $this->latitude = 'loc';
        $this->longitude = 'loc';
        $this->languages = 'country_languages';
        $this->timezone = 'timezone';
        $this->zipcode = 'postal';
        $this->isp = 'hostname';
    }
    
    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}