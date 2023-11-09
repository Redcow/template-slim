<?php

namespace Middleware;

use Infrastructure\Validation\QueryParam;
use Infrastructure\Validation\QueryParamException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use Slim\Routing\Route;
use Slim\Routing\RouteContext;

class RouteValidator implements Middleware
{

    /**
     * @throws QueryParamException
     */
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $route = $this->getRoute($request);

        $paramAssertions = $this->getParamAssertions($route->getCallable());

        $queryParams = $this->getQueryParams($request);

        $errors = $this->getErrors($paramAssertions, $queryParams);

        if(!empty($errors)) {
            throw new QueryParamException($errors);
        }

        return $handler->handle($request);
    }


    private function getRoute(Request $request): Route
    {
        $routeContext = RouteContext::fromRequest($request);

        return $routeContext->getRoute();
    }

    /**
     * @return QueryParam[]
     */
    private function getParamAssertions(string $controllerClassName): array
    {
        try {
            $controller = new ReflectionClass($controllerClassName);

            $attributes = $controller->getAttributes(
                QueryParam::class,
                ReflectionAttribute::IS_INSTANCEOF
            );

            return array_map(
                fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
                $attributes
            );
        } catch (ReflectionException) {
            // todo create DevError, for it and MissingEnvVar
            throw new \Error("Route handler is not a controller");
        }
    }

    private function getQueryParams(Request $request): array
    {
        return match ($request->getMethod()) {
            'POST' => $request->getParsedBody(),
            default => $request->getQueryParams(),
        };
    }

    /**
     * @param QueryParam[] $paramAssertions
     * @param array $queryParams
     * @return array
     */
    private function getErrors(array $paramAssertions, array $queryParams): array
    {
        $errors = [];

        foreach ($paramAssertions as $paramAssertion)
        {
            $paramErrors = $paramAssertion->check($queryParams);

            $errors = array_merge($errors, $paramErrors);
        }

        return $errors;
    }
}