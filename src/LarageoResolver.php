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

    /**
     * @var \Illuminate\Support\Collection $data
     * @var array $config //The configuration array
     */
    public function __construct(\Illuminate\Support\Collection $data, private array $config)
    {
        $this->config = $config;
        
        //Assign the driver using the \Technoyer\Larageo\LarageoDriver service
        $this->driver = new LarageoDriver($this->config);
        
        /**
         * Reading each driver properties then matching the stubs to the global response of Larageo::class
         * Assigning the values to the response keys
         */
        foreach(get_class_vars(get_class($this)) as $var => $value)
        {
            if( property_exists($this->driver->service, $var) )
            {
                if( $var!=='config' && isset($data[$this->driver->service->$var]) )
                {
                    $this->$var = $data[$this->driver->service->$var] ?? null;
                }
            }
        }

        /**
         * Some cases like the resonse of ipinfi.io, the latitude and the longtiude both in single line seprated by comma
         */
        if( Str::contains($this->latitude, ",") )
        {
            $exp_lat_lon = explode(",", $this->latitude);
            $this->latitude = $exp_lat_lon[0] ?? null;
            $this->longitude = $exp_lat_lon[1] ?? null;
        }
    }

    /**
     * Resolving the $_SERVER['HTTP_USER_AGENT'] or request()->header('User-Agent') or simply fill it manullay
     * @return void
     */
    public function resolveUserAgent(): void
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

            //fill the browser, browser_version and other stuff with values
            $get_browser_obj = get_browser($this->user_agent); //return an object
            $this->platform = $get_browser_obj->platform;
            $this->browser = $get_browser_obj->browser;
            $this->browser_version = $get_browser_obj->version;
            $this->crawler_name = trim($get_browser_obj->crawler);
            $this->is_crawler = Str::length($this->crawler_name) > 0 ? true : false;
        }
    }

    /**
     * Assignt he country values to their keys
     * @return void
     */
    public function countryAttributes(): void
    {
        if( !is_null($this->country_code) && Str::length($this->country_code) > 1 )
        {
            $this->flag = sprintf('https://flagicons.lipis.dev/flags/4x3/%s.svg', Str::lower($this->country_code));

            //Countries Resource
            $resource = new CountriesListResource($this->country_code);

            //Locale Reseources
            $locale = new LanguagesListResource($this->country_code);

            //Country Currency Resource
            $currency = new CurrenciesListResource($this->country_code);

            //Timezone Resource
            $this->timezone = new TimezondeIdentifierResource($this->country_code);

            //Simple OS Resource
            $os = new OSListResource($this->user_agent);
            
            if( !is_null($os) && $os instanceof OSListResource )
            {
                $this->os = $os->os ?? null;
            }

            //Dangerous solution, this will be according to the configuration file.
            //This method gethostbyaddr() may takes some time to response
            if( is_null($this->isp) && isset($this->config['isp_by_php']) && $this->config['isp_by_php']===true )
            {
                $this->isp = gethostbyaddr($this->ip);
            }

            $this->dial_code = $resource->dial_code;

            if( $locale instanceof \Technoyer\Larageo\resources\LanguagesListResource )
            {
                if( $locale->locale instanceof \Illuminate\Support\Collection )
                {
                    //Langues will return into \Illuminate\Support\Collection object
                    $this->languages = $locale->locale;
                }
            }
            
            if( !is_null($this->timezone) && !is_null($this->timezone->timenow) && is_object($this->timezone->timenow) )
            {
                $this->timenow = $this->timezone->timenow->datetime ?? null;
            }

            if( !is_null($currency) && $currency instanceof \Technoyer\Larageo\resources\CurrenciesListResource && !is_null($currency->currency) )
            {
                $this->currency = $currency->currency ?? null;
            }

            //just in case
            if( is_null($this->country) )
            {
                $this->country = $resource->country_name;
            }
        }
    }
}