<?php

namespace App\Message;

class SendEmailMessage
{
    private string $recipient;
    private string $name;

    public function __construct(string $recipient, string $name)
    {
        $this->recipient = $recipient;
        $this->name = $name;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
