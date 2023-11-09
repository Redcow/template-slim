<?php

namespace Model\Security\Signin;

use Infrastructure\Security\UnauthorizedException;
use Model\Security\Signin\interfaces\JwtBuilderInterface;
use Model\Security\Signin\interfaces\SignInInputInterface as InputInterface;
use Model\Security\Signin\interfaces\SignInOutputInterface as OutputInterface;
use Model\User\UserRepositoryInterface;

class Signin
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly JwtBuilderInterface $jwtBuilder
    ){}
    public function execute(InputInterface $input,OutputInterface $output): void
    {
        try {
            $user = $this->userRepository->connect(
                $input->getUser(),
                $input->getPassword()
            );
            $jwt = $this->jwtBuilder->build([
                "uid" => $user->id
            ]);
            $output->setJwt($jwt);
        } catch (UnauthorizedException $e) {
            $output->setError($e);
        }
    }
}