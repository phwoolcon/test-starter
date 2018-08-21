<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Version;

error_reporting(-1);
$_SERVER['PHWOOLCON_ENV'] = 'testing';

if (!extension_loaded('phalcon')) {
    echo $error = 'Extension "phalcon" not detected, please install or activate it.';
    throw new RuntimeException($error);
}
$cwd = getcwd();
$testRoot = $cwd . '/tests/root';
$vendorDir = $cwd . '/vendor';

// Register class loader
include $vendorDir . '/autoload.php';

define('TEST_ROOT_PATH', $testRoot);

// PHP 7.2: ini_set(): Headers already sent. You cannot change the session module's ini settings at this time
ini_get('session.use_cookies') and ini_set('session.use_cookies', 0);
ini_get('session.cache_limiter') and ini_set('session.cache_limiter', '');

// The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
$di = new FactoryDefault();
$_SERVER['PHWOOLCON_ROOT_PATH'] = TEST_ROOT_PATH;
$_SERVER['PHWOOLCON_CONFIG_PATH'] = TEST_ROOT_PATH . '/app/config';
$_SERVER['PHWOOLCON_MIGRATION_PATH'] = TEST_ROOT_PATH . '/bin/migrations';
$_SERVER['PHWOOLCON_VENDOR_PATH'] = $vendorDir;
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHWOOLCON_PHALCON_VERSION'] = (int)Version::getId();

is_file($testRootReady = TEST_ROOT_PATH . '/ready') and unlink($testRootReady);
