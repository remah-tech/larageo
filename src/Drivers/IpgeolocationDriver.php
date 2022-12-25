<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

final class IpgeolocationDriver extends LarageoDriversBase
{
    private $driver_api_key;

    public function __construct(private array $config)
    {
        $this->driver = "ipgeolocation";
        $this->driver_api_base = "https://api.ipgeolocation.io/ipgeo?apiKey=%key%&ip=%ip%&output=json";
        $this->driver_api_method = $this->config['drivers'][$this->driver]['method'] ?? 'GET';
        $this->driver_api_key = $this->config['drivers'][$this->driver]['key'] ?? null;

        //Detrmine Keys of Response
        $this->country = 'country_name';
        $this->country_code = 'country_code2';
        $this->city = 'city';
        $this->region = 'state_prov';
        $this->latitude = 'latitude';
        $this->longitude = 'longitude';
        $this->languages = 'country_languages';
        $this->timezone = 'timezone';
        $this->zipcode = 'zipcode';
        $this->isp = 'isp';
    }
    
    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}