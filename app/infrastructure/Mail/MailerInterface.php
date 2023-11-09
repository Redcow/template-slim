<?php

namespace Infrastructure\Mail;

interface MailerInterface
{
    public const DSN = 'MAILER_DNS';

    public function newEmail(): self;

    public function from(string $address): self;

    public function to(string ...$addresses): self;

    public function setSubject(string $subject): self;

    public function setBody(string $body): self;

    public function attach(string ...$path): self;

    /**
     * @throws EmailException
     */
    public function send(): void;
}