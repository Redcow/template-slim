<?php

namespace Model\Security\Signin\interfaces;

interface SignInInputInterface
{
    public function getUser(): string;

    public function getPassword():string;
}