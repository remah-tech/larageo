<?php
namespace Technoyer\Larageo\resources;

class OSListResource
{
    public $os;

    public function __construct(public $user_agent)
    {
        $this->user_agent = $user_agent;

        foreach ( $this->list() as $key => $value ) 
        { 
            if ( preg_match($key, $this->user_agent ) ) 
            {
                $this->os = $value;
            }
        }   

    }

    protected function list(): \Illuminate\Support\Collection
    {
        return collect(
            [
                '/windows nt 10/i'      =>  'Windows',
                '/windows nt 6.3/i'     =>  'Windows',
                '/windows nt 6.2/i'     =>  'Windows',
                '/windows nt 6.1/i'     =>  'Windows',
                '/windows nt 6.0/i'     =>  'Windows',
                '/windows nt 5.2/i'     =>  'Windows',
                '/windows nt 5.1/i'     =>  'Windows',
                '/windows xp/i'         =>  'Windows',
                '/windows nt 5.0/i'     =>  'Windows',
                '/windows me/i'         =>  'Windows',
                '/win98/i'              =>  'Windows',
                '/win95/i'              =>  'Windows',
                '/win16/i'              =>  'Windows',
                '/macintosh|mac os x/i' =>  'Mac OS',
                '/mac_powerpc/i'        =>  'Mac OS',
                '/linux/i'              =>  'Linux',
                '/ubuntu/i'             =>  'Ubuntu',
                '/iphone/i'             =>  'iPhone',
                '/ipod/i'               =>  'iPod',
                '/ipad/i'               =>  'iPad',
                '/android/i'            =>  'Android',
                '/blackberry/i'         =>  'BlackBerry',
                '/webos/i'              =>  'Mobile'
            ]
            );
    }
}