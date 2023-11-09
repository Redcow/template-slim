<?php

namespace Model\Author\Get\interfaces;

use Model\Author\AuthorEntity;

interface GetAuthorRepositoryInterface
{
    public function getById(int $authorId): ?AuthorEntity;
}