<?php

namespace Model\User;

use Infrastructure\Repository\DbColumn;
use Model\BaseEntity;

class UserEntity extends BaseEntity
{
    public const TYPE_USER = "user";
    public function __construct(
        #[DbColumn('email')]
        public readonly string $email,

        #[DbColumn('password')]
        public readonly ?string $password = null,

        #[DbColumn('type')]
        public readonly string $type = UserTypeEnum::USER->name,

        #[DbColumn('is_active')]
        public readonly bool $isActive = false,

        ?int $id = null
    )
    {
        parent::__construct($id);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }


}