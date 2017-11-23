<?php

namespace Phwoolcon\TestStarter\TestCase\Generator;

use Phalcon\Mvc\Router\Route;
use Phwoolcon\Router;

class RoutesInspector extends Router
{

    public function __construct($file)
    {
        $this->_routes = [];
        $routes = is_file($file) ? include $file : [];
        is_array($routes) && $this->addRoutes($routes);
    }

    public function inspect()
    {
        $this->splitRoutes();
        $testCases = [];
        foreach ($this->exactRoutes as $method => $routes) {
            if ($method == 'HEAD') {
                continue;
            }
            /* @var Route $route */
            foreach ($routes as $route) {
                $paths = $route->getPaths();
                $controller = $paths['controller'];
                $action = $paths['action'];
                if (is_string($controller) && is_callable([$controller, $action])) {
                    $testCases[$controller][$action][$method][] = $route->getPattern();
                }
            }
        }
        return $testCases;
    }
}
