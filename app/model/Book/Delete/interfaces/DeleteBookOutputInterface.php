<?php

namespace Model\Book\Delete\interfaces;

interface DeleteBookOutputInterface
{
    public function setMessage(string $message): void;
}