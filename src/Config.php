<?php

namespace Phwoolcon\TestStarter;

use Phalcon\Di;
use Phwoolcon\Config as PhwoolconConfig;

class Config extends PhwoolconConfig
{

    public static function register(Di $di)
    {
        $preloadFiles = [];
        foreach (detectPhwoolconPackageFiles($_SERVER['PHWOOLCON_VENDOR_PATH']) as $package) {
            $path = dirname(dirname($package));
            if ($files = glob($path . '/phwoolcon-package/config/*.php')) {
                $preloadFiles = array_merge($preloadFiles, $files);
            }
        }
        $rootDir = dirname($_SERVER['PHWOOLCON_VENDOR_PATH']);
        if (is_dir($dir = $rootDir . '/phwoolcon-package/config')) {
            $preloadFiles = array_merge($preloadFiles, glob($dir . '/*.php'));
        }
        if (is_dir($dir = $rootDir . '/phwoolcon-package/config')) {
            $preloadFiles = array_merge($preloadFiles, glob($dir . '/override-*/*.php'));
        }
        PhwoolconConfig::$preloadFiles = $preloadFiles;
        PhwoolconConfig::register($di);
    }
}
