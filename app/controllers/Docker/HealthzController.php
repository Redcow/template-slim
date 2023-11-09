<?php

namespace Controller\Docker;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HealthzController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $response;
    }
}