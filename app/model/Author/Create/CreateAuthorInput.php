<?php

namespace Model\Author\Create;

use Model\Author\Create\interfaces\CreateAuthorInputInterface;

class CreateAuthorInput implements CreateAuthorInputInterface
{

    private string $firstName;
    private string $lastName;
    private string $bookName;
    private int $ownerId;

    public function setIdentity(string $fullIdentityAuthor): void
    {
        [$this->firstName, $this->lastName] = explode(" ", $fullIdentityAuthor);
    }

    public function setBookName(string $bookName): void
    {
        $this->bookName = $bookName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName ?? "John";
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setOwnerId(int $ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }
}