<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

class IpgeolocationDriver extends LarageoDriversBase
{
    public function __construct()
    {
        $this->driver = "ipgeolocation";
        $this->driver_api_base = "https://api.ipgeolocation.io/ipgeo?apiKey=%key%&ip=%ip%&output=json";
        $this->driver_api_method = config( sprintf('larageo.%s.method', $this->driver) ) ?? 'GET';

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
}