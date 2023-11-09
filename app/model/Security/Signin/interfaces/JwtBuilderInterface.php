<?php

namespace Model\Security\Signin\interfaces;

interface JwtBuilderInterface
{
    public function build(array $payload = []): string;
}