<?php

namespace Phwoolcon\TestStarter\TestCase;

use File_Iterator_Facade;
use PHPUnit\Util\Fileloader;
use Phwoolcon\Controller;
use Phwoolcon\TestStarter\Exception\TestCaseGenerator\TestsExistingException;
use Phwoolcon\TestStarter\TestCase\Generator\RoutesInspector;
use Phwoolcon\Text;
use ReflectionClass;

class Generator
{
    protected $srcDir;
    protected $outputDir;
    protected $routesFile;
    protected $candidates;

    public function __construct($srcDir, $outputDir, $routesFile = '')
    {
        $this->srcDir = realpath($srcDir) . DIRECTORY_SEPARATOR;
        $this->outputDir = $outputDir;
        $this->routesFile = $routesFile;
        $outputFinder = new File_Iterator_Facade();
        if ($outputFinder->getFilesAsArray([
            $this->outputDir . '/Unit',
            $this->outputDir . '/Integration',
        ], '.php')) {
            throw new TestsExistingException();
        }
    }

    public function generate()
    {
        $candidates = $this->detectCandidates();
        return $this;
    }

    public function detectCandidates()
    {
        if ($this->candidates !== null) {
            return $this->candidates;
        }
        $this->candidates = [];

        $finder = new File_Iterator_Facade();
        $files = $finder->getFilesAsArray($this->srcDir, '.php');
        foreach ($files as $filename) {
            $this->inspectClassFile($filename);
        }

        $this->inspectRoutes();

        return $this->candidates;
    }

    protected function inspectClassFile($filename)
    {
        $classes = get_declared_classes();
        Fileloader::checkAndLoad($filename);
        $newClasses = array_diff(get_declared_classes(), $classes);
        $magicMethods = array_flip([
            '__construct',
            '__destruct',
            '__call',
            '__callStatic',
            '__get',
            '__set',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__set_state',
            '__clone',
            '__debugInfo',
        ]);
        foreach ($newClasses as $newClass) {
            $reflection = new ReflectionClass($newClass);
            if ((!Text::startsWith($reflection->getFileName(), $this->srcDir, false)) ||
                // Skip controller classes, because they will be tested via route
                ($reflection->isSubclassOf(Controller::class))
            ) {
                continue;
            }
            $methods = [];
            foreach ($reflection->getMethods() as $method) {
                if ((isset($magicMethods[$method->getName()])) ||
                    (!$method->isPublic()) ||
                    ($method->getDeclaringClass()->getName() !== $newClass)
                ) {
                    continue;
                }
                $methods[] = $method->getName();
            }
            $this->candidates['unit'][$newClass] = $methods;
        }
    }

    protected function inspectRoutes()
    {
        if ($this->routesFile && is_file($this->routesFile)) {
            $inspector = new RoutesInspector($this->routesFile);
            $this->candidates['routes'] = $inspector->inspect();
        }
    }
}
