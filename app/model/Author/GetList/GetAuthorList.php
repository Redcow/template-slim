<?php

namespace Model\Author\GetList;

use Model\Author\GetList\interfaces\GetAuthorListOutputInterface;
use Model\Author\GetList\interfaces\GetAuthorListRepositoryInterface;

class GetAuthorList
{
    public function __construct(
        private readonly GetAuthorListRepositoryInterface $authorRepository
    ) {}
    public function execute($_request, GetAuthorListOutputInterface $response): void
    {
        $authorList = $this->authorRepository->getList();

        $response->setList(...$authorList);
    }
}