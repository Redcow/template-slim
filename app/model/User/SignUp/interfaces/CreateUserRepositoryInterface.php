<?php

namespace Model\User\SignUp\interfaces;

use Infrastructure\Database\exceptions\InvalidQueryException;
use Model\User\NewUserEntity;
use Model\User\UserEntity;

interface CreateUserRepositoryInterface
{
    /**
     * @param UserEntity $user
     * @return NewUserEntity|null
     * @throws InvalidQueryException
     */
    public function save(UserEntity $user): ?NewUserEntity;
}