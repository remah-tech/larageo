<?php
namespace Technoyer\Larageo;

use Technoyer\Larageo\LarageoResolver;
use Technoyer\Larageo\Services\LarageoBase;
use Technoyer\Larageo\Contracts\LarageoContract;
use Technoyer\Larageo\Exceptions\LarageoException;

class Larageo extends LarageoBase implements LarageoContract
{    
    public $ip, $ip_version, $response_type;

    public function __construct(private array $config)
    {
        if( !array_key_exists($this->config['driver'], $this->config['drivers']) )
        {
            throw new LarageoException('Driver does not exists or not supported!');
        }

        if( isset($this->config['default_ip']) && !is_null($this->config['default_ip']) )
        {
            $this->default_ip = $this->config['default_ip'];
        }

        $this->driver = $this->config['driver'];

        $this->selectDriver();
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function resolve(): \Technoyer\Larageo\LarageoResolver|null
    {
        if( !is_null($this->response) )
        {
            $data = collect( json_decode($this->response, true) );
            
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

    public function get()
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
        
        $this->setCacheKey($this->ip);

        if( is_null($this->cached()) )
        {
            $this->response = $this->connect();
            $this->response_type = "api";

            if( $this->shouldCache() )
            {
                $this->cache();
            }
        } else {
            $this->response_type = "cache";
            $this->response = $this->cached();
        }

        return $this->response;
    }
}
