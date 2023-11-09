<?php

namespace Model\Book\Delete;

use Model\Book\Delete\interfaces\DeleteBookInputInterface;

class DeleteBookInput implements DeleteBookInputInterface
{
    private int $bookId;

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function setBookId(int $bookId): void
    {
        $this->bookId = $bookId;
    }

}