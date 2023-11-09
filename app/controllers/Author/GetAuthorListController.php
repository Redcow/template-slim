<?php

namespace Controller\Author;

use Infrastructure\Trait\jsonResponse;
use Infrastructure\Validation\QueryParam;
use Infrastructure\Validation\QueryParamNumber;
use Infrastructure\Validation\QueryParamString;
use Model\Author\GetList\GetAuthorList;
use Model\Author\GetList\GetAuthorListInput as Input;
use Model\Author\GetList\GetAuthorListOutput as Output;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

#[QueryParam(name:"test")]
#[QueryParamString(name: "titi", minLength: 10)]
#[QueryParamNumber(name: 'age', min: 10, max: 30)]
class GetAuthorListController
{
    use jsonResponse;

    public function __construct(
        private readonly GetAuthorList $useCase
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = new Input;
        $output = new Output;

        $this->useCase->execute($input, $output);

        return $this->json($output->list, $response);
    }
}