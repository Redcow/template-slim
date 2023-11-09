<?php

namespace Model\Author\Get;

use Model\Author\Get\interfaces\GetAuthorInputInterface;

class GetAuthorInput implements GetAuthorInputInterface
{

    public int $authorId;

    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}