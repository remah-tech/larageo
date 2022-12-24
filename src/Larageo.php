<?php
namespace Technoyer\Larageo;

use Technoyer\Larageo\LarageoResolver;
use Technoyer\Larageo\Services\LarageoBase;
use Technoyer\Larageo\Contracts\LarageoContract;
use Technoyer\Larageo\Exceptions\LarageoException;

class Larageo extends LarageoBase implements LarageoContract
{    
    public $ip;
    public $ip_version;

    public function __construct(private array $config)
    {
        if( !array_key_exists($this->config['driver'], $this->config['drivers']) )
        {
            throw new LarageoException('Driver does not exists or not supported!');
        }

        $this->driver = $this->config['driver'];

        $this->selectDriver();
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
        
        return $this->connect();
    }
}
