<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Service\RecipientsFactory;

final class Message implements MessageInterface
{
    private Recipients $recipients;
    private Template $template;
    private MessageType $messageType;

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     * @throws \App\Domain\Exception\MessageException
     */
    public function __construct(Template $template, string $recipient)
    {
        $this->messageType = new MessageType($recipient);
        $this->template = $template;
        $this->recipients = (new RecipientsFactory(new MessageType($recipient)))->create($recipient);
    }

    public function recipients(): Recipients
    {
        return $this->recipients;
    }

    public function template(): Template
    {
        return $this->template;
    }

    public function messageType(): MessageType
    {
        return $this->messageType;
    }
}
