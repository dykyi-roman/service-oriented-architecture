<?php

declare(strict_types=1);

namespace App\Domain\Sender\ValueObject;

use App\Domain\Sender\Exception\RecipientsException;
use App\Domain\Sender\MessageInterface;
use App\Domain\Sender\Service\RecipientsFactory;
use App\Domain\Template\ValueObject\Template;

final class Message implements MessageInterface
{
    private Recipients $recipients;
    private Template $template;

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     * @throws RecipientsException
     */
    public function __construct(Template $template, MessageType $messageType, string $recipient)
    {
        $this->template = $template;
        $this->recipients = (new RecipientsFactory($messageType))->create($recipient);
    }

    public function recipients(): Recipients
    {
        return $this->recipients;
    }

    public function template(): Template
    {
        return $this->template;
    }
}
