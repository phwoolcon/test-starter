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
        $currentPackageDir = $rootDir . '/phwoolcon-package/config';
        $hasCurrentConfig = is_dir($currentPackageDir);

        $filesToMerge = [];

        // Add configs in current package
        $filesToMerge[] = $hasCurrentConfig ? glob($currentPackageDir . '/*.php') : [];

        $packages = detectPhwoolconPackageFiles($_SERVER['PHWOOLCON_VENDOR_PATH']);
        // Add vendor configs
        $overrides = [];
        foreach ($packages as $package) {
            $packageDir = dirname(dirname($package));
            $filesToMerge[] = glob($packageDir . '/phwoolcon-package/config/*.php');
            $overrides[] = glob($packageDir . '/phwoolcon-package/config/override-*/*.php');
        }
        $filesToMerge = array_merge($filesToMerge, $overrides);
        // Add overriding configs in current package
        $filesToMerge[] = $hasCurrentConfig ? glob($currentPackageDir . '/override-*/*.php') : [];

        // Prepare preload config
        foreach ($filesToMerge as $files) {
            $config->merge(new PhalconConfig(static::loadFiles($files)));
        }
        PhwoolconConfig::$preloadConfig = $config->toArray();

        // Register testing configs
        PhwoolconConfig::register($di);
    }
}
