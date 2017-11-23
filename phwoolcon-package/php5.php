<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Fileloader;

if (PHP_VERSION_ID < 70000) {
    spl_autoload_register(function ($class) {
        $map = [
            TestCase::class   => 'PHPUnit_Framework_TestCase',
            Fileloader::class => 'PHPUnit_Util_Fileloader',
        ];
        if (isset($map[$class])) {
            class_alias($map[$class], $class);
            return true;
        }
        return false;
    });
}
