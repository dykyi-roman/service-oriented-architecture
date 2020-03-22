<?php

namespace App\Domain\Service;

use App\Domain\Exception\RecipientsException;
use App\Domain\ValueObject\EmailRecipients;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\PhoneRecipients;
use App\Domain\ValueObject\Recipients;

final class RecipientsFactory
{
    private MessageType $type;

    public function __construct(MessageType $type)
    {
        $this->type = $type;
    }

    /**
     * @inheritDoc
     * @throws RecipientsException
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function create(string $recipient): Recipients
    {
        if ($this->type->isPhone()) {
            return new PhoneRecipients($_ENV['SENDER_PHONE_NUMBER'], $recipient);
        }

        if ($this->type->isEmail()) {
            return new EmailRecipients($_ENV['SENDER_EMAIL_ADDRESS'], $recipient);
        }

        throw RecipientsException::recipientInNotFound($recipient);
    }
}
