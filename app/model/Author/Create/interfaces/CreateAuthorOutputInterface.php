<?php

namespace Model\Author\Create\interfaces;

use Model\Author\AuthorEntity;

interface CreateAuthorOutputInterface
{
    public function getNewAuthor(): ?AuthorEntity;

    public function setNewAuthor(?AuthorEntity $createdAuthor): void;
}