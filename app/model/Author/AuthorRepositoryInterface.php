<?php

namespace Model\Author;

use Model\Author\Create\interfaces\CreateAuthorRepositoryInterface;
use Model\Author\Delete\interfaces\DeleteAuthorRepositoryInterface;
use Model\Author\Get\interfaces\GetAuthorRepositoryInterface;
use Model\Author\GetList\interfaces\GetAuthorListRepositoryInterface;

interface AuthorRepositoryInterface
    extends CreateAuthorRepositoryInterface,
            GetAuthorRepositoryInterface,
            GetAuthorListRepositoryInterface,
            DeleteAuthorRepositoryInterface
{}