<?php

namespace Phwoolcon\TestStarter;

use Phalcon\Config as PhalconConfig;
use Phalcon\Di;
use Phwoolcon\Config as PhwoolconConfig;

class Config extends PhwoolconConfig
{

    public static function register(Di $di)
    {
        $config = new PhalconConfig();
        $rootDir = dirname($_SERVER['PHWOOLCON_VENDOR_PATH']);
        if (is_dir($dir = $rootDir . '/phwoolcon-package/config')) {
            $config->merge(new PhalconConfig(static::loadFiles(glob($dir . '/*.php'))));
        }
        foreach ($packageFiles = detectPhwoolconPackageFiles($_SERVER['PHWOOLCON_VENDOR_PATH']) as $package) {
            $path = dirname(dirname($package));
            if ($files = glob($path . '/phwoolcon-package/config/*.php')) {
                $config->merge(new PhalconConfig(static::loadFiles($files)));
            }
        }
        foreach ($packageFiles as $package) {
            $path = dirname(dirname($package));
            if ($files = glob($path . '/phwoolcon-package/config/override-*/*.php')) {
                $config->merge(new PhalconConfig(static::loadFiles($files)));
            }
        }
        if (is_dir($dir = $rootDir . '/phwoolcon-package/config')) {
            $config->merge(new PhalconConfig(static::loadFiles(glob($dir . '/override-*/*.php'))));
        }
        PhwoolconConfig::$preloadConfig = $config->toArray();
        PhwoolconConfig::register($di);
    }
}
