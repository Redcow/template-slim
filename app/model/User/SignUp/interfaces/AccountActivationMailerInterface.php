<?php

namespace Model\User\SignUp\interfaces;

interface AccountActivationMailerInterface
{
    public function sendAccountActivationLink(string $link, string $userEmail): void;
}