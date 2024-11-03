<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendUserNotificationEmail(User $user): void
    {
        $email = (new Email())
            ->from('codninja2.0@gmail.com') // Update the sender email here
            ->to($user->getEmail())
            ->subject('User Data Stored')
            ->text(sprintf('Hello %s, your data has been successfully stored.', $user->getName()));

        $this->mailer->send($email);
    }
}
