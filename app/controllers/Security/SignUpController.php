<?php

declare(strict_types=1);

namespace Controller\Security;

use Infrastructure\Trait\jsonResponse;
use Infrastructure\Validation\QueryParamString;
use Model\User\SignUp\SignUp;
use Model\User\SignUp\SignUpInput as Input;
use Model\User\SignUp\SignUpOutput as Output;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

#[QueryParamString(name: 'email', required: true)]
#[QueryParamString(name: 'password', required: true, minLength: 8, specialCharacter: true)]
class SignUpController
{
    use jsonResponse;

    public function __construct(
       private readonly SignUp $useCase,
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $urlGenerator = fn ($token) => $routeParser->fullUrlFor(
            $request->getUri(),
            'activate-account',
            ['token' => $token]
        );

        $input = new Input(
            $request->getParsedBody()['email'],
            $request->getParsedBody()['password'],
            $urlGenerator
        );

        $output = new Output();

        $this->useCase->execute($input, $output);

        if($output->getUser() === null) {
            return $this->json([
                'message' => 'Erreur lors de la création de l\'utilisateur'
            ], $response->withStatus(400));
        }

        return $this->json([
            'message' => $output->getMessage()
        ], $response);
    }
}