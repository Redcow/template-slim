<?php

namespace Model\Author\Create\interfaces;

interface CreateAuthorInputInterface
{
    public function getFirstName(): string;

    public function getLastName(): string;

    public function getOwnerId(): int;
}