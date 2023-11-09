<?php

namespace API\public;

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

use function Config\loadEnvConfig;
use function Service\loadServices;
use function Middleware\addMiddlewares;
use function Route\addRoutes;

loadEnvConfig();
loadServices();

$app = AppFactory::create();

addRoutes($app);

addMiddlewares($app);

$app->run();