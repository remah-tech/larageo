<?php
namespace Technoyer\Larageo;

use Illuminate\Support\Str;
use Technoyer\Larageo\LarageoDriver;
use Technoyer\Larageo\resources\OSListResource;
use Technoyer\Larageo\resources\TimeNowResource;
use Technoyer\Larageo\Exceptions\LarageoException;
use Technoyer\Larageo\resources\CountriesListResource;
use Technoyer\Larageo\resources\LanguagesListResource;
use Technoyer\Larageo\resources\CurrenciesListResource;
use Technoyer\Larageo\resources\TimezondeIdentifierResource;

class LarageoResolver
{    
    /**
     * The public properties for response
     */
    public $ip,
            $ip_version = null, //IPV4 or IPV6
            $country,
            $country_code,
            $city,
            $region,
            $latitude,
            $longitude,
            $dial_code,
            $languages = [],
            $currency,
            $timezone,
            $timenow,
            $isp,
            $flag,
            $zipcode,
            $security = [],
            $os,
            $device,
            $platform,
            $browser,
            $browser_version,
            $is_crawler,
            $crawler_name,
            $user_agent;

    public function __construct(\Illuminate\Support\Collection $data, private array $config)
    {
        $this->config = $config;
        $this->driver = new LarageoDriver($this->config['driver']);
        
        foreach(get_class_vars(get_class($this)) as $var => $value)
        {
            if( property_exists($this->driver->service, $var) )
            {
                if( isset($data[$this->driver->service->$var]) )
                {
                    $this->$var = $data[$this->driver->service->$var] ?? null;
                }
            }
        }

        if( Str::contains($this->latitude, ",") )
        {
            $exp_lat_lon = explode(",", $this->latitude);
            $this->latitude = $exp_lat_lon[0] ?? null;
            $this->longitude = $exp_lat_lon[1] ?? null;
        }
    }

    public function resolveUserAgent()
    {
        if( !is_null($this->user_agent) )
        {
            /**
             * MobileDetect Class copyrights reserved to :
             * https://github.com/serbanghita/Mobile-Detect
             * By default compser will install it while installing Larageo package, But just in case..
             */
            if( class_exists(\Detection\MobileDetect::class) )
            {
                $device = new \Detection\MobileDetect();
                $device->setUserAgent($this->user_agent); //set to Detection\MobileDetect
                $this->device = ($device->isMobile() ? ($device->isTablet() ? 'Tablet' : 'Mobile') : 'Computer');
            } else {
                $detect = Str::lower($this->user_agent);
                $this->device = (is_numeric(strpos($detect, "mobile")) ? (is_numeric(strpos($detect, "tablet")) ? 'Tablet' : 'Mobile') : 'Computer');
            }

            $get_browser_obj = get_browser($this->user_agent);
            $this->platform = $get_browser_obj->platform;
            $this->browser = $get_browser_obj->browser;
            $this->browser_version = $get_browser_obj->version;
            $this->crawler_name = trim($get_browser_obj->crawler);
            $this->is_crawler = Str::length($this->crawler_name) > 0 ? true : false;
        }
    }

    public function countryAttributes()
    {
        if( !is_null($this->country_code) )
        {
            $this->flag = sprintf('https://flagicons.lipis.dev/flags/4x3/%s.svg', Str::lower($this->country_code));

            $resource = new CountriesListResource($this->country_code);
            $locale = new LanguagesListResource($this->country_code);
            $currency = new CurrenciesListResource($this->country_code);
            $this->timezone = new TimezondeIdentifierResource($this->country_code);
            $os = new OSListResource($this->user_agent);
            
            if( !is_null($os) && $os instanceof OSListResource )
            {
                $this->os = $os->os ?? null;
            }

            if( is_null($this->isp) && isset($this->config['isp_by_php']) && $this->config['isp_by_php']===true )
            {
                $this->isp = gethostbyaddr($this->ip);
            }

            $this->dial_code = $resource->dial_code;
            $this->languages = $locale;

            if( !is_null($this->timezone) && !is_null($this->timezone->timenow) )
            {
                $this->timenow = $this->timezone->timenow->datetime ?? null;
            }

            if( !is_null($currency) && !is_null($currency->currency) )
            {
                $this->currency = $currency->currency ?? null;
            }

            if( is_null($this->country) )
            {
                $this->country = $resource->country_name;
            }
        }
    }
}