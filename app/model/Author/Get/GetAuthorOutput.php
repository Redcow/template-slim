<?php

namespace Model\Author\Get;

use Model\Author\AuthorEntity;
use Model\Author\Get\interfaces\GetAuthorOutputInterface;

class GetAuthorOutput implements GetAuthorOutputInterface
{
    public ?AuthorEntity $author;

    public function setAuthor(?AuthorEntity $author): void
    {
        $this->author = $author;
    }
}