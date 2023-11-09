<?php

namespace Model\Security\Signin;

use Model\Security\Signin\interfaces\SignInInputInterface;

class SignInInput implements SignInInputInterface
{
    public function __construct(
        public readonly string $username,
        public readonly string $password
    ){}

    public function getUser(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}