<?php

namespace Infrastructure\Mail;

abstract class MailerFactory
{
    abstract public function get(): MailerInterface;
}