<?php

namespace Model\Security;

use Infrastructure\Security\UnauthorizedException;
use Model\Security\Signin\interfaces\JwtBuilderInterface;

interface JwtServiceInterface extends JwtBuilderInterface
{

    /**
     * @throws UnauthorizedException
     */
    public function decrypt(string $token): void;
}