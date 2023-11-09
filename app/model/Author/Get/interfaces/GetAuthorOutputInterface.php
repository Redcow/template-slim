<?php

namespace Model\Author\Get\interfaces;

use Model\Author\AuthorEntity;

interface GetAuthorOutputInterface
{
    public function setAuthor(?AuthorEntity $author): void;
}