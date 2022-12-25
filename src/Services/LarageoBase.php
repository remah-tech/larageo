<?php
namespace Technoyer\Larageo\Services;

use Illuminate\Support\Str;
use Technoyer\Larageo\LarageoDriver;
use Technoyer\Larageo\Drivers\MaptilerDriver;
use Technoyer\Larageo\Services\LarageoClient;
use Technoyer\Larageo\Exceptions\LarageoException;
use Illuminate\Support\Facades\Cache;

class LarageoBase extends LarageoClient
{
    public $driver, $device, $ip, $ip_version, $user_agent, $platform;

    private $agent_keys = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP',
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
        'HTTP_X_CLUSTER_CLIENT_IP',
    ];

    private $default_ip = '8.8.8.8';
    private $default_user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040803 Firefox/0.9.3';
    public $cache_key_stub = 'ip-%s';
    public $cache_time = 24 * 60 * 60; //in seconds
    private $cache_key;

    public function selectDriver(array $config): void
    {
        $driver = new LarageoDriver($config);
        
        $this->driver_api_base = $driver->driver_api_base;
        $this->driver_api_method = $driver->driver_api_method;
        
        $this->setApiKey($driver->getApiKey());
    }

    /**
     * The IP setter, we can set a defined value or auto detect the client ip address.
     * @var string $ip nullable
     * @return void
     */
    public function ip(string $ip=null): object
    {
        $this->ip = $ip;

        if( is_null($ip) )
        {
            $this->ip = $this->getIp();
        }

        return $this;
    }
    
    /**
     * Get the agent IP address, try using Laravel request or get it by ourselves
     * @return string
     */
    protected function getIp(): string
    {
        if( is_null($ip = request()->ip()) ) //trying Laravel first
        {
            foreach($this->agent_keys as $key)
            {
                if( !is_null($address = getenv($key)) )
                {
                    if( false!==strpos($address, "," ) )
                    {
                        foreach(explode(",", $address) as $ipaddress)
                        {
                            $ip = trim($ipaddress); //safe
                        }
                    }
                }
            }
        }

        //Validate ip address or return the default IP 127.0.0.1
        if( $this->validate($ip) )
        {
            return $ip;
        }

        return $this->default_ip;
    }

    /**
     * Validate IP address and set the ip version
     * @var string $ip nullable, once it was null, the method depends on the $this->ip value
     * @return boolean 
     */
    public function validate($ip=null): bool
    {
        if( $this->ip===null && $ip===null )
        {
            throw new LarageoException('This method requires an ip address to check!');
        }

        $this->ip = $ip;

        //IPV4 check
        if( filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) )
        {
            $this->ip_version = 'IPV4';
        }

        //IPV6 check
        if( filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) )
        {
            $this->ip_version = 'IPV6';
        }

        if( is_null($this->ip_version) )
        {
            return false;
        }

        return true;
    }

    public function userAgent($user_agent=null)
    {
        if( is_null($this->user_agent = $user_agent) )
        {
            $this->user_agent = request()->header('User-Agent') ?? $_SERVER['HTTP_USER_AGENT'];
        }

        if( is_null($this->user_agent) || $this->user_agent==='Symfony' )
        {
            $this->user_agent = $this->default_user_agent;
        }
    }

    public function setCacheKey($ip=null)
    {
        $this->cache_key = sprintf($this->cache_key_stub, $ip ?? $this->ip);
    }

    public function getCacheKey()
    {
        return $this->cache_key;
    }

    public function cached()
    {
        return Cache::get($this->cache_key);
    }

    public function shouldCache()
    {
        if( isset($this->getConfig()['cache']) && true===$this->getConfig()['cache'] )
        {
            return true;
        }

        return false;
    }

    public function cache()
    {
        if( $this->shouldCache() && !is_null($this->response) && is_null($this->cached()) )
        {
            Cache::remember($this->cache_key, $this->cache_time, fn() => $this->response);
        }
    }
}