<?php

namespace Phwoolcon\TestStarter;

use Phalcon\Config as PhalconConfig;
use Phalcon\Di;
use Phalcon\Logger;
use Phwoolcon\Config as PhwoolconConfig;

class Config extends PhwoolconConfig
{

    public static function register(Di $di)
    {
        $config = new PhalconConfig();
        foreach (detectPhwoolconPackageFiles($_SERVER['PHWOOLCON_VENDOR_PATH']) as $package) {
            $path = dirname(dirname($package));
            if ($files = glob($path . '/phwoolcon-package/config/*.php')) {
                $config->merge(new PhalconConfig(static::loadFiles($files)));
            }
        }
        parent::register($di);
        PhwoolconConfig::$config = $config->merge(new PhalconConfig(static::$config))->toArray();
        PhwoolconConfig::set('app.log.level', Logger::DEBUG);
    }
}
