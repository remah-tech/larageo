<?php
/**
 * The Larageo package config
 * Package git: https://github.com/Technoyer/larageo
 */
return
    [
        /**
         * Enable getting ISP/hostname using gethostbyaddr() may take time to response.
         * If the driver API returned null ISP value, we may use this feature if you enable it.
         * Its up to you!
         */
        'isp_by_php' => false,

        /**
         * Using the Laravel cache power for storing the API response for each IP address
         * true = to enable this feature (recommended)
         */
        'cache' => true,

        /**
         * Define the default driver (IP Whois Service)
         */
        'driver' => 'ip2location',
        
        /**
         * List of supported drivers and their options configuration
         */
        'drivers' => [
            /**
             * maptiler.com
             * 100k requests / Month (Free)
             */
            'maptiler' => 
            [
                'pro' => false, //set it to true if you have PRO plan
                'key' => env('MAPTILER_KEY'),
                'method' => 'GET',
            ],

            /**
             * ip2location.io
             * 30k requests / Month (Free)
             */
            'ip2location' => 
            [
                'pro' => false, //set it to true if you have PRO plan
                'key' => env('IP2LOCATION_KEY'),
                'method' => 'GET'
            ],

            /**
             * ipinfo.io
             * 50k requests / Month (Free)
             */
            'ipinfo' => 
            [
                'pro' => false, //set it to true if you have PRO plan
                'key' => env('IPINFO_TOKEN'),
                'method' => 'GET',
            ],

            /**
             * ipgeolocation.io
             * 1k requests / Day (Free)
             */
            'ipgeolocation' => 
            [
                'pro' => false, //set it to true if you have PRO plan
                'key' => env('IPGEOLOCATION_KEY'),
                'method' => 'GET'
            ],

            /**
             * ipapi.co
             * 1k requests / Day (Free)
             */
            'ipapico' => 
            [
                'pro' => false, //set it to true if you have PRO plan
                'key' => env('IPAPICO_KEY') ?? null, //not required in the FREE plans
                'method' => 'POST'
            ],
            
            /**
             * ipapi.com
             * 45 requests / Minute (Free)
             */
            'ipapicom' => 
            [
                'pro' => false, //set it to true if you have PRO plan
                'key' => env('IPAPICOM_KEY') ?? null, //not required in the FREE plans
                'method' => 'GET'
            ],

            /**
             * apiip.net
             * 5k requests / Month (Free)
             */
            'apiip' => 
            [
                'pro' => false, //set it to true if you have PRO plan
                'key' => env('APIIP_KEY'),
                'method' => 'GET'
            ]
        ],

        /**
         * Default IP Address
         * We use this option for testing purpose or when something goes wrong.
         */
        'default_ip' => '8.8.8.8',
    ];
?>