<?php

namespace Phwoolcon\TestStarter;

use Phalcon\Di;
use PHPUnit\Framework\TestCase as PhpunitTestCase;
use Phwoolcon\Aliases;
use Phwoolcon\Cache;
use Phwoolcon\Cache\Clearer;
use Phwoolcon\Config;
use Phwoolcon\Cookies;
use Phwoolcon\Db;
use Phwoolcon\DiFix;
use Phwoolcon\Events;
use Phwoolcon\I18n;
use Phwoolcon\Log;
use Phwoolcon\Session;
use Phwoolcon\Util\Counter;
use Phwoolcon\Util\Timer;

class TestCase extends PhpunitTestCase
{
    use RemoteCoverageTrait;

    /**
     * @var Di
     */
    protected $di;

    protected function prepareTestRoot($testRootReady)
    {
        $assembler = new ResourceAggregator(getcwd());
        $assembler->aggregate();
        touch($testRootReady);
    }

    protected function reloadConfig()
    {
        Config::register($this->di);
    }

    public function setUp()
    {
        is_file($testRootReady = TEST_ROOT_PATH . '/ready') || $this->prepareTestRoot($testRootReady);

        /* @var Di $di */
        $di = $this->di = Di::getDefault();
        Events::register($di);
        DiFix::fix($di);
        Db::register($di);
        Cache::register($di);
        Log::register($di);
        $this->reloadConfig();
        Counter::register($this->di);
        Aliases::register($di);
        I18n::register($di);
        Cookies::register($di);
        Session::register($di);
        Clearer::clear();

        $class = get_class($this);
        Log::debug("================== Running {$class}::{$this->getName()}() ... ==================");
        Timer::start();
    }

    public function tearDown()
    {
        $elapsed = Timer::stop();
        parent::tearDown();
        Log::debug("================== Finished, time elapsed: {$elapsed}. ==================");
    }
}
