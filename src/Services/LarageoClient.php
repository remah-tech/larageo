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

    private $http_client = 'cURL';

    public function setHttpClient($http_client)
    {
        $this->http_client = $http_client;
        return $this;
    }

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
        
        if( $this->http_client==='Laravel' )
        {
            $this->response = $this->GuzzleClient();
        } else {
            $this->response = $this->Curl();
        }
        
        return $this->resolve();
    }
}