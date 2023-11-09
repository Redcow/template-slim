<?php

namespace Model\User;

use Infrastructure\Security\UnauthorizedException;
use Model\User\SignUp\interfaces\CreateUserRepositoryInterface;

interface UserRepositoryInterface extends CreateUserRepositoryInterface
{
    /**
     * @throws UnauthorizedException
     */
    public function connect(string $username, string $password): UserEntity;
}