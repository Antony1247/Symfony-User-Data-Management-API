<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use App\Service\EmailService;
use Psr\Log\LoggerInterface;
use App\Entity\User;

class SendEmailMessageHandler
{
    private EmailService $emailService;
    private LoggerInterface $logger;

    public function __construct(EmailService $emailService, LoggerInterface $logger)
    {
        $this->emailService = $emailService;
        $this->logger = $logger;
    }

    public function __invoke(SendEmailMessage $message)
    {
        $this->logger->info('Received SendEmailMessage for: ' . $message->getRecipient());

        try {
            $user = new User();
            $user->setEmail($message->getRecipient());
            $user->setName($message->getName());
            $this->emailService->sendUserNotificationEmail($user);
            $this->logger->info('Email sent to: ' . $user->getEmail());
        } catch (\Exception $e) {
            $this->logger->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
