<?php

namespace Model\User\SignUp;

use Infrastructure\Database\exceptions\InvalidQueryException;
use Infrastructure\Database\exceptions\SqlExceptionTypeEnum;
use Model\User\SignUp\interfaces\AccountActivationMailerInterface;
use Model\User\SignUp\interfaces\SignUpInputInterface as Input;
use Model\User\SignUp\interfaces\SignUpOutputInterface as Output;
use Model\User\UserEntity;
use Model\User\UserRepositoryInterface;

readonly class SignUp
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private AccountActivationMailerInterface $mailer
    ) {}

    public function execute(Input $input, Output $output): void
    {
        $newUser = new UserEntity(
            $input->getEmail(),
            UserEntity::hashPassword($input->getPassword()),
            UserEntity::TYPE_USER
        );

        try {
            $inactiveUser = $this->repository->save($newUser);

            $url = $input->getUrlForAccountActivation($inactiveUser->token);

            $this->mailer->sendAccountActivationLink($url, $inactiveUser->email);

            $output->setUser($inactiveUser);
            $output->setMessage("Your account has been created, check your mailing {$inactiveUser->email}");
        } catch (InvalidQueryException $exception) {
            if($exception->getType() === SqlExceptionTypeEnum::DUPLICATE) {
                $output->setMessage("The email {$input->getEmail()} is already registered");
            }
        }

    }
}