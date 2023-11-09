<?php

namespace Model\Book\Delete\interfaces;

use Infrastructure\Security\ForbiddenException;
use Infrastructure\Security\UnauthorizedException;
use Model\Book\BookEntity;

interface DeleteBookRepositoryInterface
{
    /**
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function getById(int $bookId): ?BookEntity;

    public function delete(BookEntity $book): void;



    public function getByAuthorId(int $authorId): array;

    public function countAuthorBooks(int $authorId): int;

    public function deleteById(int $bookId): void;
}