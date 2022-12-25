<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

final class IpapicomDriver extends LarageoDriversBase
{
    private $driver_api_key;

    public function __construct(private array $config)
    {
        $this->driver = "ipapicom";
        $this->driver_api_base = "http://api.ipapi.com/api/%ip%?access_key=%key%";
        $this->driver_api_method = $this->config['drivers'][$this->driver]['method'] ?? 'GET';
        $this->driver_api_key = $this->config['drivers'][$this->driver]['key'] ?? null;

        //Detrmine Keys of Response
        $this->country = 'country_name';
        $this->country_code = 'country_code';
        $this->city = 'city';
        $this->region = 'region_name';
        $this->latitude = 'latitude';
        $this->longitude = 'longitude';
        $this->languages = 'languages';
        $this->timezone = 'timezone';
        $this->zipcode = 'zip';
        $this->isp = 'hostname';
    }
    
    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}