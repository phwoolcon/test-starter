<?php

namespace Phwoolcon\TestStarter\Unit;

use Exception;
use Phwoolcon\TestStarter\Exception\TestCaseGenerator\TestsExistingException;
use Phwoolcon\TestStarter\TestCase;

class GeneratorTest extends TestCase
{
    protected $generator;

    protected function getGenerator()
    {
        if ($this->generator === null) {
            $cwd = getcwd();
            $src = $cwd . '/tests/resource/src';
            $output = $cwd . '/tests/root/storage/test-cases';
            $routesFile = $cwd . '/tests/resource/routes.php';
            removeDir($output);
            $this->generator = new TestCase\Generator($src, $output, $routesFile);
        }
        return $this->generator;
    }

    public function testDetectSourceCodes()
    {
        $generator = $this->getGenerator();
        $result = $generator->detectCandidates();
        $this->assertEquals([
            'unit' => [
                'Phwoolcon\\TestStarter\\Foo\\Model\\IntegrationTest' => [
                    'coverMe',
                ],

                'Phwoolcon\\TestStarter\\Foo\\NeedTestForMe' => [
                    'visibleMethod',
                    'coverTraitMethodInClass',
                ],
            ],

            'routes' => [
                'Phwoolcon\\TestStarter\\Foo\\NeedTest\\InDeep\\SubFolder\\ThereIsAController' => [
                    'coverMeViaRoutes' => [
                        'GET' => [
                            '/cover-this-route',
                        ],
                    ],
                ],
            ],
        ], $result);
    }

    public function testGenerate()
    {
        $generator = $this->getGenerator();
        $generator->detectCandidates();
        $generator->generate();
        $this->assertTrue(true);
    }

    public function testTestsExisting()
    {
        $e = null;
        try {
            $outputDir = getcwd() . '/tests';
            new TestCase\Generator('', $outputDir);
        } catch (Exception $e) {
        }
        $this->assertInstanceOf(TestsExistingException::class, $e);
    }
}
