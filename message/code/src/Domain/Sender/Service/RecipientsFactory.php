<?php

namespace App\Domain\Sender\Service;

use App\Domain\Sender\Exception\RecipientsException;
use App\Domain\Sender\ValueObject\EmailRecipients;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Sender\ValueObject\PhoneRecipients;
use App\Domain\Sender\ValueObject\Recipients;

final class RecipientsFactory
{
    private MessageType $type;

    public function __construct(MessageType $type)
    {
        $this->type = $type;
    }

    /**
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
