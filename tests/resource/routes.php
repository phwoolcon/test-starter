<?php
/* @var Phwoolcon\Router $this */

use Phwoolcon\TestStarter\Foo\NeedTest\InDeep\SubFolder\ThereIsAController;

$this->addRoutes([
    'GET' => [
        'cover-this-route' => [ThereIsAController::class, 'coverMeViaRoutes'],
    ],
]);
