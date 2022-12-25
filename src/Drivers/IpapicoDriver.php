<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

class IpapicoDriver extends LarageoDriversBase
{
    public function __construct()
    {
        $this->driver = "ipapico";
        $this->driver_api_base = "https://ipapi.co/%ip%/json/";
        $this->driver_api_method = config( sprintf('larageo.%s.method', $this->driver) ) ?? 'GET';

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
}