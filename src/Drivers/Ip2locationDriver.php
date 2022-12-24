<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

class Ip2locationDriver extends LarageoDriversBase
{
    public function __construct()
    {
        $this->driver = "ip2location";
        $this->driver_api_base = "https://api.ip2location.io/?key=%key%&ip=%ip%&format=json";
        $this->driver_api_method = config( sprintf('larageo.%s.method', $this->driver) ) ?? 'GET';

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
}