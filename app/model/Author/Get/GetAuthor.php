<?php

namespace Model\Author\Get;

use Model\Author\Get\interfaces\GetAuthorInputInterface;
use Model\Author\Get\interfaces\GetAuthorOutputInterface;
use Model\Author\Get\interfaces\GetAuthorRepositoryInterface;

class GetAuthor
{
    public function __construct(
        private readonly GetAuthorRepositoryInterface $authorRepository
    ) {}
    public function execute(GetAuthorInputInterface $input, GetAuthorOutputInterface $output): void
    {
        $author = $this->authorRepository->getById($input->getAuthorId());
        $output->setAuthor($author);
    }
}