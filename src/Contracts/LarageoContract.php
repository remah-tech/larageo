<?php
namespace Technoyer\Larageo\Contracts;

interface LarageoContract
{
    public function ip(string $ip=null);
    public function resolve();
    public function get();
}