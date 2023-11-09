<?php

namespace Model\Author\Create\interfaces;

use Model\Author\AuthorEntity;

interface CreateAuthorRepositoryInterface
{
    public function save(AuthorEntity $author): ?AuthorEntity;

    public function isStoredAuthor(AuthorEntity $author): bool;
}