<?php

namespace Model\Book\Delete;

use Model\Book\Delete\interfaces\DeleteBookOutputInterface;

class DeleteBookOutput implements DeleteBookOutputInterface
{
    private string $message;

    public function getMessage(): string
    {
        return $this->message;
    }
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}