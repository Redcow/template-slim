<?php

namespace Model\Security\Signin\interfaces;

use Infrastructure\Security\UnauthorizedException;

interface SignInOutputInterface
{
    public function setJwt(string $token): void;

    public function setError(UnauthorizedException $exception): void;
}