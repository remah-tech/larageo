<?php
namespace Technoyer\Larageo\resources;

use Illuminate\Support\Str;
use Technoyer\Larageo\Services\LarageoClientBase;

class TimezondeIdentifierResource extends LarageoClientBase
{
    private $base = "http://worldtimeapi.org/api/timezone/%s";
    private $timenow_response;

    public $timezone;
    public $timenow;
    public $response;

    public function __construct(private string $country_code)
    {
        if( Str::length($this->country_code) > 1 )
        {
            $this->country_code = Str::upper($country_code);

            $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $this->country_code);
            
            if( is_array($timezone) && count($timezone) > 0 )
            {
                $this->timezone = $timezone[0];
            }
    
            if( $this->timezone instanceof TimezondeIdentifierResource )
            {
                $this->timezone = $this->timezone->timezone;
            }
    
            $this->resolveTimeNow();
        }
    }

    public function resolveTimeNow()
    {
        $this->driver_api_base = sprintf($this->base, $this->timezone);
        $this->driver_api_method = 'GET';

        $use = 'laravel';
        if( !class_exists(\Illuminate\Support\Facades\Http::class) )
        {
            $use = 'CURL';
        }

        $output = null;

        if( $use==='laravel' )
        {
            $output = $this->GuzzleClient();
        } else {
            $output = $this->Curl();
        }

        $this->timenow = json_decode($output);
    }
}