<?php
namespace Technoyer\Larageo\Services;

class LarageoClientBase
{    
    /**
     * Use the Laravel HTTP Client to connect to the API
     * @return string|null
     */
    public function GuzzleClient(): string|null
    {
        $client = new \Illuminate\Support\Facades\Http;

        //define the connection method
        $response = $this->driver_api_method=='GET' 
            ? $client::get($this->driver_api_base)
            : $client::post($this->driver_api_base)
        ;

        if( $response->ok() )
        {
            return $response->body(); //the response data
        }
        
        return null;
    }

    /**
     * Use the PHP cURL to connect to the API
     * @return string|null
     */
    public function Curl(): string|null
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->driver_api_base);
        $this->driver_api_method=='GET' ?? curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch); //the response data

        curl_close ($ch);

        return $output;
    }
}