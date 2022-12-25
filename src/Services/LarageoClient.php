<?php
namespace Technoyer\Larageo\Services;

use Illuminate\Support\Str;
use Technoyer\Larageo\Services\LarageoClientBase;

class LarageoClient extends LarageoClientBase
{
    public $ip;
    public $response;
    public $response_type = 'api';

    /**
     * The API for drivers and its creds
     */
    public $driver;
    public $driver_api_base;
    public $driver_api_method;
    private $driver_api_key;

    /**
     * Set the driver API key or token
     * @var string $key nullable (some drivers do not ask for token or key when you use the free edition)
     * @return void
     */
    public function setApiKey(string $key=null): void
    {
        $this->driver_api_key = $key;
    }

    public function connect()
    {
        $this->driver_api_base = Str::replace("%key%", $this->driver_api_key, $base_url ?? $this->driver_api_base);
        $this->driver_api_base = Str::replace("%ip%", $this->ip, $this->driver_api_base);
        
        $use = 'laravel';
        if( !class_exists(\Illuminate\Support\Facades\Http::class) )
        {
            $use = 'CURL';
        }

        if( $use==='laravel' )
        {
            $this->response = $this->GuzzleClient();
        } else {
            $this->response = $this->Curl();
        }
        
        return $this->resolve();
    }
}