<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

class MaptilerDriver extends LarageoDriversBase
{
    public function __construct()
    {
        $this->driver = "maptiler";
        $this->driver_api_base = "https://api.maptiler.com/geolocation/ip.json?key=%key%";
        $this->driver_api_method = config( sprintf('larageo.%s.method', $this->driver) ) ?? 'GET';

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
}