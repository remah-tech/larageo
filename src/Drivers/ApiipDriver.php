<?php
namespace Technoyer\Larageo\Drivers;

use Technoyer\Larageo\Drivers\LarageoDriversBase;

final class ApiipDriver extends LarageoDriversBase
{
    private $driver_api_key;

    public function __construct(private array $config)
    {
        $this->driver = "apiip";
        
        //This driver requires http:// for free accounts
        $this->schema = 'http';
        if( $this->config['drivers'][$this->driver]['pro'] )
        {
            $this->schema = 'https';
        }

        $this->driver_api_base = $this->schema."://apiip.net/api/check?ip=%ip%&accessKey=%key%";
        $this->driver_api_method = $this->config['drivers'][$this->driver]['method'] ?? 'GET';
        $this->driver_api_key = $this->config['drivers'][$this->driver]['key'] ?? null;
        
        //Detrmine Keys of Response
        $this->country = 'countryName';
        $this->country_code = 'countryCode';
        $this->city = 'city';
        $this->region = 'regionName';
        $this->latitude = 'latitude';
        $this->longitude = 'longitude';
        $this->languages = 'languages';
        $this->timezone = 'timezone';
        $this->zipcode = 'postalCode';
        $this->isp = 'hostname';
    }
    
    public function getApiKey()
    {
        return $this->driver_api_key;
    }
}