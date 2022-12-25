<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

final class Ip2locationDriver extends LarageoDriversBase
{
    private $driver_api_key;

    public function __construct(private array $config)
    {
        $this->driver = "ip2location";
        $this->driver_api_base = "https://api.ip2location.io/?key=%key%&ip=%ip%&format=json";
        $this->driver_api_method = $this->config['drivers'][$this->driver]['method'] ?? 'GET';
        $this->driver_api_key = $this->config['drivers'][$this->driver]['key'] ?? null;

        //Detrmine Keys of Response
        $this->country = 'country_name';
        $this->country_code = 'country_code';
        $this->city = 'city_name';
        $this->region = 'region_name';
        $this->latitude = 'latitude';
        $this->longitude = 'longitude';
        $this->languages = 'country_languages';
        $this->timezone = 'time_zone';
        $this->zipcode = 'zip_code';
        $this->isp = 'as';
    }

    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}