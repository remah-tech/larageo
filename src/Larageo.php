<?php
namespace Technoyer\Larageo;

use Technoyer\Larageo\LarageoResolver;
use Technoyer\Larageo\Services\LarageoBase;
use Technoyer\Larageo\Contracts\LarageoContract;
use Technoyer\Larageo\Exceptions\LarageoException;

class Larageo extends LarageoBase implements LarageoContract
{    
    public $ip, $ip_version, $response_type;

    /**
     * @var array $config //The configuration array
     * @var string $http_client //switch between Laravel & cURL
     */
    public function __construct(private array $config, $http_client="cURL")
    {
        $this->http_client = $http_client;

        if( !array_key_exists($this->config['driver'], $this->config['drivers']) )
        {
            throw new LarageoException('Driver ('.html_entity_decode($this->config['driver']).') does not exists or not supported!');
        }

        if( isset($this->config['default_ip']) && !is_null($this->config['default_ip']) )
        {
            $this->default_ip = $this->config['default_ip'];
        }

        if( !is_null($this->http_client) )
        {
            $this->setHttpClient($this->http_client);
        } else {
            if( class_exists(\Illuminate\Support\Facades\Http::class) )
            {
                $this->setHttpClient('Laravel');
            }
        }

        $this->setDriver($this->config['driver']);

        $this->selectDriver($this->config);
    }

    /**
     * Retrieve the private option config
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set the driver value if neccessary
     * @var string $driver
     */
    public function setDriver(string $driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * Get the driver setted before value
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Resolve the api connection response
     * @return \Technoyer\Larageo\LarageoResolver|null
     */
    public function resolve(): \Technoyer\Larageo\LarageoResolver|null
    {
        if( !is_null($this->response) )
        {
            //convert it to collection \Illuminate\Support\Collection
            $data = collect( json_decode($this->response, true) );
            
            //set the LarageoResolver options values
            $resolved = new LarageoResolver($data, $this->config);
            $resolved->ip = $this->ip;
            $resolved->ip_version = $this->ip_version;
            $resolved->user_agent = $this->user_agent;
            
            $resolved->resolveUserAgent();
            $resolved->countryAttributes();
            
            return $resolved;
        }

        return null;
    }

    /**
     * The final station to retrieve the response data of the IP address tracking process
     * @return \Technoyer\Larageo\LarageoResolver|null
     */
    public function get(): \Technoyer\Larageo\LarageoResolver|null
    {
        if( is_null($this->ip) )
        {
            $this->ip();
        } else {
            $this->validate($this->ip);
        }

        if( is_null($this->user_agent) )
        {
            $this->userAgent();
        }
        
        //Set the cache key
        $this->setCacheKey($this->ip);

        //Check be default if there was an item stored in the cahce with the prev cache key
        if( is_null($this->cached()) )
        {
            $this->response = $this->connect();
            $this->response_type = "api";

            //If we should store the response in the cache driver
            if( $this->shouldCache() )
            {
                $this->cache(); //do it
            }
        } else {
            //ELSE retrieve the cached IP response data
            $this->response_type = "cache";
            $this->response = $this->cached();
        }

        return $this->response;
    }
}
