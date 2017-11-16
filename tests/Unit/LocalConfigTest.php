<?php

namespace Phwoolcon\TestStarter\Unit;

use Phwoolcon\Config;
use Phwoolcon\TestStarter\TestCase;

class LocalConfigTest extends TestCase
{

    public function testGetPackageConfig()
    {
        $this->assertEquals('Phwoolcon', Config::get('app.name'));
    }

    public function testGetLocalConfig()
    {
        $this->assertEquals('bar-starter', Config::get('app.foo'));
    }
}
