<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

class IpapicomDriver extends LarageoDriversBase
{
    public function __construct()
    {
        $this->driver = "ipapicom";
        $this->driver_api_base = "http://api.ipapi.com/api/%ip%?access_key=%key%";
        $this->driver_api_method = config( sprintf('larageo.%s.method', $this->driver) ) ?? 'GET';

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
}