<?php

namespace App\Domain\Service;

use App\Domain\ValueObject\EmailRecipients;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\PhoneRecipients;
use App\Domain\ValueObject\Recipients;

final class RecipientsFactory
{
    private MessageType $messageType;

    public function __construct(MessageType $messageType)
    {
        $this->messageType = $messageType;
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function create(string $recipient): Recipients
    {
        if ($this->messageType->isPhone()) {
            return new PhoneRecipients($_ENV['SENDER_PHONE_NUMBER'], $recipient);
        }

        return new EmailRecipients($_ENV['SENDER_EMAIL_ADDRESS'], $recipient);
    }
}
