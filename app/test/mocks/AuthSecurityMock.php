<?php

namespace Test\mocks;

use Infrastructure\Security\AuthSecurity;
use Infrastructure\Security\UnauthorizedException;

class AuthSecurityMock extends AuthSecurity
{
    public static function mockUserId(int $id): void
    {
        self::$isAllowed = true;
        self::$userId = $id;
    }
}