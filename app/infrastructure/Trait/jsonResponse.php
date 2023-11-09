<?php

namespace Infrastructure\Trait;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

trait jsonResponse
{
    function json(array|JsonSerializable $payload, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write(
            json_encode($payload)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }
}