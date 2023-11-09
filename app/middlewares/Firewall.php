<?php

declare(strict_types=1);

namespace Middleware;

use Infrastructure\Security\AuthSecurity;
use Infrastructure\Security\UnauthorizedException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

/**
 * Middleware de sécurité
 * à déclencher avant les controllers pour controler le jwt au besoin
 */
final class Firewall implements Middleware
{
    private array $publicPaths = [];

    public function __construct(array $config = [])
    {
        if(array_key_exists('publicPaths', $config) && is_array($config['publicPaths'])) {
            $this->publicPaths = $config['publicPaths'];
        }
    }

    /**
     * @throws UnauthorizedException
     */
    public function process(Request $request, RequestHandler $handler): Response
    {

        if($this->isPublic($request)) {
            AuthSecurity::allowPublic();
            return $handler->handle($request);
        }

        $authorizationHeader = $request->getHeaderLine('Authorization');

        if(empty($authorizationHeader)) {
            throw new UnauthorizedException('Vous devez vous connectez');
        }

        list($_bearer, $token) = explode(' ', $authorizationHeader, 2);

        AuthSecurity::extractJwt($token);

        return $handler->handle($request);
    }

    private function isPublic(Request $request): bool
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $routeName = $route->getName();
        $routePattern = $route->getPattern();

        return in_array($routePattern, $this->publicPaths) || in_array($routeName, $this->publicPaths);
    }
}