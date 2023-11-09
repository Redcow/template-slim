<?php

namespace Model\User\SignUp;

use Model\User\SignUp\interfaces\SignUpOutputInterface;
use Model\User\UserEntity;

class SignUpOutput implements SignUpOutputInterface
{
    private ?UserEntity $user = null;
    private ?string $message = null;
    public function setUser(?UserEntity $storedUser): void
    {
        $this->user = $storedUser;
    }

    public function getUser(): ?UserEntity
    {
        return $this->user;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}