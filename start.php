<?php

use Phalcon\Di\FactoryDefault;
use PHPUnit\Framework\TestCase;

if (!class_exists(TestCase::class) && class_exists(PHPUnit_Framework_TestCase::class)) {
    class_alias(PHPUnit_Framework_TestCase::class, TestCase::class);
}

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
$_SERVER['PHWOOLCON_VENDOR_PATH'] = $vendorDir;

$mockCompiledFiles = [
    '/vendor/phwoolcon/class_aliases.php',
    '/vendor/phwoolcon/assets.php',
    '/vendor/phwoolcon/admin_assets.php',
    '/vendor/phwoolcon/commands.php',
];
foreach ($mockCompiledFiles as $mockFile) {
    $filename = $testRoot . $mockFile;
    is_dir($dir = dirname($filename)) or mkdir($dir, 0777, true);
    fileSaveArray($filename, []);
}
$storageDirs = ['cache', 'logs', 'session'];
foreach ($storageDirs as $dir) {
    $dir = storagePath($dir);
    removeDir($dir);
    mkdir($dir, 0777, true);
}
