<?php

namespace Middleware;

use Model\MailerServiceInterface;
use Slim\App;

/**
 * Etape de chargement des middlewares pour l'application
 * @param App $app
 * @return void
 */
function addMiddlewares(App $app): void
{
    // handle security route
    addFirewalMiddleware($app);

    // handle request params validation
    $app->addMiddleware( new RouteValidator() );

    // keep it bottom
    $app->addRoutingMiddleware();
    addErrorHandlerMiddleware($app);
}

function addFirewalMiddleware(App $app): void
{
    $app->addMiddleware( New Firewall([
        'publicPaths' => [
            '/',
            'sign_in',
            'sign_up',
            'activate_account',
            'docker_health'
            //'get_authors'
        ]
    ]) );
}

function addErrorHandlerMiddleware(App $app): void
{
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    $resolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();

    $errorHandler = new ErrorHandler(
        $resolver,
        $responseFactory,
        $app->getContainer()->get(MailerServiceInterface::class)
    );

    $errorMiddleware->setDefaultErrorHandler($errorHandler);
}
