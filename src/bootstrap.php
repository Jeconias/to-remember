<?php

namespace StreamData;

use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$routes = require __DIR__ . '/app/routes.php';
$dependencies = require __DIR__ . '/dependency-container.php';

return function () use ($routes, $dependencies) {
    $app = AppFactory::createFromContainer($dependencies);
    $app->addErrorMiddleware(true, true, true);

    $routes($app);

    return $app;
};
