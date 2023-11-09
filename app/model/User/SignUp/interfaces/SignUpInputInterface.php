<?php

namespace Model\User\SignUp\interfaces;

interface SignUpInputInterface
{
    public function getEmail(): string;
    public function getPassword(): string;
    public function getUrlForAccountActivation(string $token): string;
}