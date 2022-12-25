<?php

use Technoyer\Larageo\Larageo;
use Technoyer\Larageo\Tests\TestCase;

final class BasicTest extends TestCase
{
    private $class = Larageo::class;

    public function testClassExists()
    {
        $this->assertTrue( class_exists($this->class) );
    }

    public function testDriverSetter()
    {
        $this->assertEquals( $this->LaraInstance()->getDriver(), 'ip2location');
    }

    public function testGetData()
    {
        $response = $this->LaraInstance();
        $output = $response->ip('161.185.160.91')->get();
        
        $this->assertNotNull($output);
        $this->assertInstanceOf('Technoyer\Larageo\LarageoResolver', $output);
        $this->assertEquals($output->ip, '161.185.160.91');
        $this->assertEquals($output->ip_version, 'IPV4');
        $this->assertNotEquals($output->ip_version, 'IPV6');
    }

    public function LaraInstance()
    {
        return new Larageo($this->sampleConfig(), 'cURL');
    }

    public function sampleConfig()
    {
        return 
        [
            'isp_by_php' => false,
            'cache' => false,
            'driver' => 'ip2location',
            'drivers' => [
                'maptiler' => 
                [
                    'pro' => false,
                    'key' => 'exj8f7SHhYGP9Iaw0GKS',
                    'method' => 'GET',
                ],
                'ip2location' => 
                [
                    'pro' => false,
                    'key' => '8392222acfde6f6f7a143a3f8e49f24d',
                    'method' => 'GET'
                ]
            ],
            'default_ip' => '8.8.8.8',
        ];
    }
}
