<?php

namespace Service;

use Config\EnvProvider;
use Infrastructure\Mail\EmailException;
use Infrastructure\Mail\MailerFactory;
use Infrastructure\Mail\MailerInterface;
use Infrastructure\Mail\templates\AccountMail;
use Infrastructure\Mail\templates\InternalMail;
use Model\MailerServiceInterface;

class MailerService
    implements MailerServiceInterface
{
    private MailerInterface $mailer;

    public function __construct(
        MailerFactory $mailerFactory
    ) {
        $this->mailer = $mailerFactory->get();
    }

    /**
     * @throws EmailException
     */
    public function sendAccountActivationLink(string $link, string $userEmail): void
    {
        $this->mailer->newEmail()
                     ->setSubject('Activation de votre compte')
                     ->from('api@cogelec.fr')
                     ->to($userEmail)
                     ->setBody(AccountMail::ActivateAccount($link))
                     ->send();
    }

    /**
     * @throws EmailException
     */
    public function sendUnknownErrorWarning(\Throwable $exception): void
    {
        $this->mailer->newEmail()
                    ->from(EnvProvider::get('APP_NAME').'@cogelec.fr')
                    ->to('119-api@cogelec.fr')
                    ->setBody(InternalMail::UnknownError($exception))
                    ->setSubject('API UNKNOWN ERROR')
                    ->send();
    }
}