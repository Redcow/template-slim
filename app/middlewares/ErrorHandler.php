<?php

namespace Middleware;

use Infrastructure\Exceptions\AppError;
use Infrastructure\Exceptions\ExceptionEnum;
use Infrastructure\Logger\LogCriticalityEnum;
use Infrastructure\Logger\Logger;
use Infrastructure\Mail\EmailException;
use Infrastructure\Mail\SymfonyMailer;
use Infrastructure\Trait\jsonResponse;
use Model\MailerServiceInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Service\MailerService;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Psr7\Response;
use Throwable;

final class ErrorHandler extends SlimErrorHandler implements ErrorHandlerInterface
{
    use jsonResponse;

    private readonly MailerServiceInterface $mailerService;

    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        MailerServiceInterface $mailerService,
        ?LoggerInterface $logger = null
    )
    {
        parent::__construct($callableResolver, $responseFactory, $logger);
        $this->mailerService = $mailerService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface
    {

        if($exception instanceof HttpNotFoundException) {
            $exception = $this->handleNotFound($exception);
        }

        if ($exception instanceof AppError) {
            return $this->logKnownError($exception);
        } else {
            return $this->logUnknownError($exception);
        }
    }

    /**
     * Log les erreurs connues dans l'application par les CodeMessageError
     * @param AppError $exception
     * @return ResponseInterface
     */
    private function logKnownError(AppError $exception): ResponseInterface
    {
        // log
        Logger::log(
            LogCriticalityEnum::WARNING,
            $exception->getMessage(),
            ['exception' => $exception]
        );
        return $this->json(
            $exception,
            new Response($exception->getCode())
        );
    }

    /**
     * Log les Error ou Exceptions non traitées dans l'app par les CodeMessageError
     * @param Throwable $exception
     * @return ResponseInterface
     * @throws EmailException
     */
    private function logUnknownError(Throwable $exception): ResponseInterface
    {
        // log
        Logger::log(
            LogCriticalityEnum::ERROR,
            "unkown error triggered : {$exception->getMessage()}",
            ['traces' => $exception->getTraceAsString()]
        );

        $this->mailerService->sendUnknownErrorWarning($exception);

        return $this->json(
            [
                'message' => 'Une erreur inattendue est survenue, nos services ont été prévenus du problème'
            ],
            new Response(500)
        );
    }

    private function handleNotFound(HttpNotFoundException $exception) {
        return new AppError(ExceptionEnum::NOT_FOUND, "route not found",404);
    }
}