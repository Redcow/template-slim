<?php

namespace Model\User\SignUp\interfaces;

use Model\User\NewUserEntity;

interface SignUpOutputInterface
{
    public function setUser(NewUserEntity $storedUser): void;
    public function setMessage(string $message): void;
}