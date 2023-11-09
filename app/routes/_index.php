<?php

namespace Route;

use Slim\App;

function register(App $app, string $path): void
{
    (require $path)($app);
}

function addRoutes(App $app): void
{
    // author routes
    register($app, __DIR__.'/AuthorRoutes.php');
    // book routes
    register($app, __DIR__.'/BookRoutes.php');
    // security routes
    register($app, __DIR__.'/SecurityRoutes.php');
    // docker healthCheck route
    register($app, __DIR__.'/DockerRoutes.php');
}