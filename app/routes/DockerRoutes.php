<?php

namespace Route;

use Controller\Docker\HealthzController;
use Slim\App;

return function (App $app) {

    $app->get('/healthz', HealthzController::class)->setName('docker_health');

};