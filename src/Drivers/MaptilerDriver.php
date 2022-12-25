<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

final class MaptilerDriver extends LarageoDriversBase
{    
    public function __construct(private array $config)
    {
        $this->driver = "maptiler";
        $this->driver_api_base = "https://api.maptiler.com/geolocation/ip.json?key=%key%";
        $this->driver_api_method = $this->config['drivers'][$this->driver]['method'] ?? 'GET';
        $this->driver_api_key = $this->config['drivers'][$this->driver]['key'] ?? null;

        //Detrmine Keys of Response
        $this->country = 'country';
        $this->country_code = 'country_code';
        $this->city = 'city';
        $this->region = 'region';
        $this->latitude = 'latitude';
        $this->longitude = 'longitude';
        $this->languages = 'country_languages';
        $this->timezone = 'timezone';
        $this->zipcode = 'postal';
    }

    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}