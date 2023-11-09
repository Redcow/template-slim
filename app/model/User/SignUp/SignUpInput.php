<?php

namespace Model\User\SignUp;

use Closure;
use Model\User\SignUp\interfaces\SignUpInputInterface;

readonly class SignUpInput implements SignUpInputInterface
{
    public function __construct(
        public string  $email,
        public string  $password,
        public Closure $urlGenerator
    ) {}
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUrlForAccountActivation(string $token): string
    {
        $generator = $this->urlGenerator;
        return $generator($token);
    }
}