<?php

namespace Phwoolcon\TestStarter;

use Phalcon\Di;
use PHPUnit\Framework\TestCase as PhpunitTestCase;
use Phwoolcon\Cache\Clearer;
use Phwoolcon\Config;
use Phwoolcon\Log;
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
        return $assembler;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function reloadConfig()
    {
        Config::register($this->di);
    }

    public function setUp()
    {
        $firstRun = !is_file($testRootReady = TEST_ROOT_PATH . '/ready');
        $firstRun && $assembler = $this->prepareTestRoot($testRootReady);
        $di = Di::getDefault();
        include TEST_ROOT_PATH . '/vendor/phwoolcon/di.php';
        $this->di = $di;
        Clearer::clear();
        isset($assembler) && Config::get('database.default') && $assembler->runMigrations($this->di);
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
