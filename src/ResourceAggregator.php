<?php

namespace Phwoolcon\TestStarter;

use Symfony\Component\Console\Output\ConsoleOutput;

class ResourceAggregator
{
    const FLAG_CONTINUE = 0b0001;
    const FLAG_SPACE_PAD = 0b0010;
    const FLAG_SPACE_PAD_CONTINUE = 0b0011;

    protected $cliOutput;
    protected $packages = [];
    protected $rootDir;
    protected $testRootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
        $this->testRootDir = $rootDir . '/tests/root';
    }

    public function aggregate()
    {
        $this->resetTestRoot();
        $this->detectPackages();

        $this->installAliases();
        $this->installConfig();
        $this->installDi();
        $this->installLocale();
        $this->installMigrations();
        $this->installRoutes();
        $this->installViews();
        $this->appendLocalResources();
    }

    protected function appendLocalResources()
    {
        $this->message('Appending local resources...', static::FLAG_SPACE_PAD_CONTINUE);
        $source = $this->rootDir . '/tests/resource';
        $destination = $this->testRootDir;
        symlinkDirOverride($source, $destination);
        $this->message(' <info>[ OK ]</info>');
    }

    protected function detectPackages()
    {
        $this->message('Detecting packages...', static::FLAG_SPACE_PAD_CONTINUE);
        $packageFiles = detectPhwoolconPackageFiles($this->rootDir . '/vendor');
        $packageFiles = array_merge($packageFiles, glob($this->rootDir . '/phwoolcon-package/*package*.php'));
        foreach ($packageFiles as $file) {
            // @codeCoverageIgnoreStart
            if (!is_array($package = include($file))) {
                continue;
            }
            // @codeCoverageIgnoreEnd
            $path = dirname(dirname($file));
            $package['path'] = $path;
            $this->packages[$file] = $package;
        }
        $this->message(' <info>[ OK ]</info>');
    }

    protected function installAliases()
    {
        $this->message('Installing class aliases...', static::FLAG_SPACE_PAD_CONTINUE);
        $aliases = [];
        foreach ($this->packages as $packageFile => $package) {
            foreach ($package as $name => $resources) {
                if (empty($resources['class_aliases']) || !is_array($resources['class_aliases'])) {
                    continue;
                }
                foreach ($resources['class_aliases'] as $sort => $detectedAliases) {
                    $aliases[$sort . '-' . $name] = $detectedAliases;
                }
            }
        }
        $target = $this->testRootDir . '/vendor/phwoolcon/class_aliases.php';
        fileSaveArray($target, arraySortedMerge($aliases));
        $this->message(' <info>[ OK ]</info>');
    }

    protected function installConfig()
    {
        $this->message('Installing config...', static::FLAG_SPACE_PAD_CONTINUE);
        foreach ($this->packages as $packageFile => $package) {
            $path = $package['path'];
            array_map($installer = function ($source) {
                $configPath = $this->testRootDir . '/app/config';
                $configFile = basename($source);
                $subDir = basename(dirname($source)) . '/';
                $subDir == 'config/' && $subDir = '';
                $destination = $configPath . '/' . $subDir . $configFile;
                is_file($destination) && unlink($destination);
                symlinkRelative($source, $destination);
            }, glob($path . '/phwoolcon-package/config/*.php'));
            array_map($installer, glob($path . '/phwoolcon-package/config/override-*/*.php'));
        }
        $this->message(' <info>[ OK ]</info>');
    }

    protected function installDi()
    {
        $this->message('Installing DI...', static::FLAG_SPACE_PAD_CONTINUE);
        $diFiles = [];
        foreach ($this->packages as $packageFile => $package) {
            $path = $package['path'];
            foreach ($package as $name => $resources) {
                if (empty($resources['di'])) {
                    continue;
                }
                foreach ((array)$resources['di'] as $sort => $file) {
                    $diFiles[$sort][] = $path . '/phwoolcon-package/' . $file;
                }
            }
        }
        $target = $this->testRootDir . '/vendor/phwoolcon/di.php';
        fileSaveInclude($target, arraySortedMerge($diFiles));
        $this->message(' <info>[ OK ]</info>');
    }

    protected function installLocale()
    {
        $this->message('Installing locales...', static::FLAG_SPACE_PAD_CONTINUE);
        $localePath = $this->testRootDir . '/app/locale/';
        foreach ($this->packages as $packageFile => $package) {
            $path = $package['path'];
            if ($items = glob($path . '/phwoolcon-package/locale/*')) {
                foreach ($items as $source) {
                    $destination = $localePath . basename($source);
                    symlinkDirOverride($source, $destination);
                }
            }
        }
        $this->message(' <info>[ OK ]</info>');
    }

    protected function installMigrations()
    {
        $this->message('Installing migrations...', static::FLAG_SPACE_PAD_CONTINUE);
        $migrationPath = $this->testRootDir . '/bin/migrations/';
        foreach ($this->packages as $packageFile => $package) {
            $path = $package['path'];
            if ($items = glob($path . '/phwoolcon-package/migrations/*')) {
                // @codeCoverageIgnoreStart
                foreach ($items as $source) {
                    $destination = $migrationPath . basename($source);
                    is_file($destination) && unlink($destination);
                    symlinkRelative($source, $destination);
                }
                // @codeCoverageIgnoreEnd
            }
        }
        $this->message(' <info>[ OK ]</info>');
    }

    protected function installRoutes()
    {
        $this->message('Installing routes...', static::FLAG_SPACE_PAD_CONTINUE);
        $routeFiles = [];
        foreach ($this->packages as $packageFile => $package) {
            $path = $package['path'];
            foreach ($package as $name => $resources) {
                if (empty($resources['routes'])) {
                    continue;
                }
                // @codeCoverageIgnoreStart
                foreach ((array)$resources['routes'] as $sort => $file) {
                    $routeFiles[$sort][] = $path . '/phwoolcon-package/' . $file;
                }
                // @codeCoverageIgnoreEnd
            }
        }
        $target = $this->testRootDir . '/vendor/phwoolcon/routes.php';
        fileSaveInclude($target, arraySortedMerge($routeFiles));
        $this->message(' <info>[ OK ]</info>');
    }

    protected function installViews()
    {
        $this->message('Installing views...', static::FLAG_SPACE_PAD_CONTINUE);
        $viewPath = $this->testRootDir . '/app/views/';
        foreach ($this->packages as $packageFile => $package) {
            $path = $package['path'];
            if ($items = glob($path . '/phwoolcon-package/views/*')) {
                foreach ($items as $source) {
                    $destination = $viewPath . basename($source);
                    symlinkDirOverride($source, $destination);
                }
            }
        }
        $this->message(' <info>[ OK ]</info>');
    }

    protected function message($message, $flag = null)
    {
        $eol = !($flag & static::FLAG_CONTINUE);
        $spacePad = $flag & static::FLAG_SPACE_PAD;
        if (!$this->cliOutput) {
            $this->cliOutput = new ConsoleOutput();
        }
        $message = is_array($message) ? implode(PHP_EOL, $message) : $message;
        $message .= $spacePad ? $this->spacePad($message) : '';
        $this->cliOutput->write($message, $eol);
    }

    protected function resetTestRoot()
    {
        $this->message('Resetting test root...', static::FLAG_SPACE_PAD_CONTINUE);
        removeDir($this->testRootDir);
        $assembleDirs = [
            '/app/config',
            '/app/locale',
            '/app/views',
            '/bin/migrations',
            '/storage/cache',
            '/storage/logs',
            '/storage/session',
            '/storage/remote-coverage',
            '/vendor/phwoolcon',
        ];
        foreach ($assembleDirs as $dir) {
            mkdir($this->testRootDir . $dir, 0777, true);
        }
        $mockCompiledFiles = [
            '/vendor/phwoolcon/assets.php',
            '/vendor/phwoolcon/admin_assets.php',
            '/vendor/phwoolcon/commands.php',
        ];
        foreach ($mockCompiledFiles as $mockFile) {
            $filename = $this->testRootDir . $mockFile;
            fileSaveArray($filename, []);
        }
        $this->message(' <info>[ OK ]</info>');
    }

    protected function spacePad($str, $length = 40)
    {
        $spaces = $length - strlen($str);
        return $spaces > 0 ? str_repeat(' ', $spaces) : ' ';
    }
}
