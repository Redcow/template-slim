<?php

namespace Model\Book\Delete;

use Infrastructure\Security\ForbiddenException;
use Infrastructure\Security\UnauthorizedException;
use Model\Author\Delete\interfaces\DeleteAuthorRepositoryInterface as AuthorRepository;
use Model\Book\Delete\interfaces\DeleteBookInputInterface as Input;
use Model\Book\Delete\interfaces\DeleteBookOutputInterface as Output;
use Model\Book\Delete\interfaces\DeleteBookRepositoryInterface as BookRepository;

class DeleteBook
{
    public function __construct(
        private readonly BookRepository   $bookRepository,
        private readonly AuthorRepository $authorRepository
    ) {}

    private const MESSAGE_FORBIDDEN_DELETE = "It's not your book!";
    private const MESSAGE_NOT_LOGGED_IN = "You have to login to do that";
    private const MESSAGE_SUCCESS = "Your book has been deleted";

    public function execute(Input $input, Output $output): void
    {
        try {
            $book = $this->bookRepository->getById($input->getBookId());

            $this->bookRepository->delete($book);

            if ($this->authorHasNoBookAnymore($book->author)) {
                $this->authorRepository->deleteById($book->author);
            }

            $output->setMessage(self::MESSAGE_SUCCESS);
        } catch (UnauthorizedException $_e) {
            $output->setMessage(self::MESSAGE_NOT_LOGGED_IN);
        } catch (ForbiddenException $_e) {
            $output->setMessage(self::MESSAGE_FORBIDDEN_DELETE);
        }

    }

    private function authorHasNoBookAnymore(int $authorId): bool
    {
        return $this->bookRepository->countAuthorBooks($authorId) === 0;
    }
}