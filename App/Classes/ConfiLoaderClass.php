<?php

namespace App\Classes;

use Symfony\Component\Config\Loader\FileLoader;

class ConfiLoaderClass extends FileLoader{
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

?>