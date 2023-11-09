<?php

namespace Controller\Security;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ActivateAccountController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        var_dump($args);

        echo "bonjour";

        return $response;
    }
}