<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

class IpinfoDriver extends LarageoDriversBase
{
    public function __construct()
    {
        $this->driver = "ipinfo";
        $this->driver_api_base = "https://ipinfo.io/%ip%/json?token=%key%";
        $this->driver_api_method = config( sprintf('larageo.%s.method', $this->driver) ) ?? 'GET';

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
}