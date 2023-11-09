<?php

namespace Model\User;

use Infrastructure\Repository\DbColumn;

class NewUserEntity extends UserEntity
{
    public function __construct(
        string $email,
        string $type,

        #[DbColumn('token')]
        public readonly string $token,

        int $id
    )
    {
        parent::__construct(
            email: $email,
            type: $type,
            id: $id
        );
    }
}