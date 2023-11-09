<?php

namespace Controller\Security;

use Infrastructure\Trait\jsonResponse;
use Infrastructure\Validation\QueryParamString;
use Model\Security\Signin\Signin;
use Model\Security\Signin\SignInInput as Input;
use Model\Security\Signin\SignInOutput as Output;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

#[QueryParamString(name: 'user', required: true)]
#[QueryParamString(name: 'password', required: true)]
class SignInController
{
    use jsonResponse;
    public function __construct(
        private readonly Signin $useCase
    ){}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = new Input(
            $request->getParsedBody()['user'],
            $request->getParsedBody()['password']
        );

        $output = new Output;

        $this->useCase->execute($input, $output);

        if(!$output->hasError() && $output->getToken()) {
            return $this->json(['token' => $output->getToken()], $response);
        } else {
            return $this->json(['message' => $output->getMessage()], $response->withStatus($output->getCode()));
        }
    }
}