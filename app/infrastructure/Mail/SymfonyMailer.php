<?php

namespace Infrastructure\Mail;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SymfonyMailer extends MailerFactory implements MailerInterface
{
    private Mailer $mailer;

    private ?Email $email;

    public function newEmail(): self
    {
        $this->email = new Email();

        return $this;
    }

    public function from(string $address): self
    {
        $this->email->from($address);

        return $this;
    }

    public function to(string ...$addresses): self
    {
        $this->email->to(...$addresses);

        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->email->subject($subject);

        return $this;
    }

    public function setBody(string $body): self
    {
        $this->email->html($body);

        return $this;
    }

    public function attach(string ...$paths): self
    {
        foreach ($paths as $path) {
            $this->email->attachFromPath($path);
        }

        return $this;
    }

    /**
     * @throws EmailException
     */
    public function send(): void
    {

        try {
            $this->mailer->send($this->email);
        } catch (TransportExceptionInterface $e) {
            echo $e->getMessage();

            $addresses = $this->email->getTo();

            throw new EmailException(
                array_map(fn (Address $address) => $address->toString(), $addresses)
            );
        }
    }

    public function get(): MailerInterface
    {
        $transport = Transport::fromDsn('smtp://mailer:1025');
        $mailerSymfony =  new self();
        $mailerSymfony->mailer = new Mailer($transport);
        return $mailerSymfony;
    }
}