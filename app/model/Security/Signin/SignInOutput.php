<?php

namespace Model\Security\Signin;

use Infrastructure\Security\UnauthorizedException;
use Model\Security\Signin\interfaces\SignInOutputInterface;

class SignInOutput implements SignInOutputInterface
{
    private ?string $jwt = null;
    private ?string $message = null;
    private ?int $code = null;

    public function getToken(): string
    {
        return $this->jwt;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setJwt(string $token): void
    {
        $this->jwt = $token;
    }

    public function hasError(): bool
    {
        return $this->message !== null || $this->code !== null;
    }

    public function setError(UnauthorizedException $exception): void
    {
        $this->message = $exception->getMessage();
        $this->code = $exception->getCode();
    }
}