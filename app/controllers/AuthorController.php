<?php

namespace Controller;

use DI\Container;
use Infrastructure\Trait\jsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Model\Author\AuthorRepositoryInterface;

use Model\Author\Get\GetAuthor;
use Model\Author\Get\GetAuthorInput;
use Model\Author\Get\GetAuthorOutput;

use Model\Author\GetList\GetAuthorList;
use Model\Author\GetList\GetAuthorListInput;
use Model\Author\GetList\GetAuthorListOutput;

use Model\Author\Create\CreateAuthor;
use Model\Author\Create\CreateAuthorInput;
use Model\Author\Create\CreateAuthorOutput;

class AuthorController
{
    use jsonResponse;

    public function __construct(
        private readonly Container $container,
        private readonly AuthorRepositoryInterface $authorRepository,
    ) {}


    public function getAuthors (Request $_request, Response $response, array $_args): Response
    {
        $input = new GetAuthorListInput;
        $output = new GetAuthorListOutput;
        $getAuthorList = new GetAuthorList($this->authorRepository);
        $getAuthorList->execute($input, $output);

        return $this->json($output->list, $response);
    }

    public function createAuthor (Request $request, Response $response, array $_args): Response
    {
        $input = new CreateAuthorInput;
        $input->setIdentity($request->getParsedBody()['name']);
        $output = new CreateAuthorOutput;
        $createAuthor = new CreateAuthor($this->authorRepository);

        // éxécution du traitement
        $createAuthor->execute($input, $output);

        // utilisation des résultats du traitement
        if($output->getNewAuthor() !== null) {
            return $this->json(['new' => $output->getNewAuthor()], $response);
        } else {
            return $this->json([], $response->withStatus(400));
        }
    }

    public function getAuthor (Request $_request, Response $response, array $args): Response
    {
        $input = new GetAuthorInput();
        $input->authorId = $args['id'];

        $output = new GetAuthorOutput();

        $getAuthor = new GetAuthor($this->authorRepository);

        $getAuthor->execute($input, $output);

        if($output->author !== null) {
            return $this->json($output->author, $response);
        } else {
            return $this->json([], $response->withStatus(404));
        }
    }

    public function deleteAuthor (Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write("Hello world! delete author");
        return $response;
    }
}