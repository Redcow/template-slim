<?php

namespace Model\Author\GetList;

use Model\Author\AuthorEntity;
use Model\Author\GetList\interfaces\GetAuthorListOutputInterface;

class GetAuthorListOutput implements GetAuthorListOutputInterface
{
    public array $list = [];

    public function setList(AuthorEntity ...$authorList): void
    {
        $this->list = $authorList;
    }
}