<?php

namespace Controller\Book;

use Infrastructure\Trait\jsonResponse;
use Infrastructure\Validation\QueryParamNumber;
use Model\Book\Delete\DeleteBook;
use Model\Book\Delete\DeleteBookInput as Input;
use Model\Book\Delete\DeleteBookOutput as Output;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

#[QueryParamNumber(name: 'id', required: true)]
class DeleteBookController
{
    use jsonResponse;

    public function __construct(
        private readonly DeleteBook $useCase
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $idBook = (int) $args['id'];

        $input = new Input();
        $input->setBookId($idBook);
        $output = new Output();

        $this->useCase->execute($input, $output);

        return $this->json([
            'message' => $output->getMessage()
        ], $response);
    }
}