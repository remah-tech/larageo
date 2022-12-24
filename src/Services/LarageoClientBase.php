<?php
namespace Technoyer\Larageo\Services;

class LarageoClientBase
{    
    public function GuzzleClient(): string|null
    {
        $client = new \Illuminate\Support\Facades\Http;

        $response = $this->driver_api_method=='GET' 
            ? $client::get($this->driver_api_base)
            : $client::post($this->driver_api_base)
        ;

        if( $response->ok() )
        {
            return $response->body();
        }
        
        return null;
    }

    public function Curl(): string|null
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->driver_api_base);
        $this->driver_api_method=='GET' ?? curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close ($ch);

        return $output;
    }
}