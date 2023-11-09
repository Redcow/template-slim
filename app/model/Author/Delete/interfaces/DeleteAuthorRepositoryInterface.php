<?php

namespace Model\Author\Delete\interfaces;

use Infrastructure\Security\ForbiddenException;
use Infrastructure\Security\UnauthorizedException;

interface DeleteAuthorRepositoryInterface
{
    //public function delete(AuthorEntity $author): void;

    /**
     * @throws UnauthorizedException|ForbiddenException
     */
    public function deleteById(int $authorId): void;
}