<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;


class ConfFileLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);
        $config = parse_ini_file($path, true);

        return $config;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'conf' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}

$loader = new ConfFileLoader(new FileLocator(__DIR__));
$config = $loader->load('sip_additional.conf');

foreach($config as $key => $val){
    if(!empty($val['callerid'])){
        echo "{$key} => ".preg_replace("/\s?<\d+>/", "", $val['callerid'])."<br/>";   
    }
}