<?php

namespace Model\Author\Create;

use Model\Author\AuthorEntity;
use Model\Author\Create\interfaces\CreateAuthorOutputInterface;

class CreateAuthorOutput implements CreateAuthorOutputInterface
{
    private ?AuthorEntity $createdAuthor;
    public function getNewAuthor(): ?AuthorEntity
    {
        return $this->createdAuthor;
    }

    public function setNewAuthor(?AuthorEntity $createdAuthor): void
    {
        $this->createdAuthor = $createdAuthor;
    }
}