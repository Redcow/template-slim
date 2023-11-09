<?php

namespace Model\Author\GetList\interfaces;

use Model\Author\AuthorEntity;

interface GetAuthorListOutputInterface
{
    public function setList(AuthorEntity ...$authorList): void;
}