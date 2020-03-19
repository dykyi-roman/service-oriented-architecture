<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Service\RecipientsFactory;

final class Message implements MessageInterface
{
    private Recipients $recipients;
    private Template $template;

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function __construct(Template $template, MessageType $messageType, string $to)
    {
        $this->template = $template;
        $this->recipients = (new RecipientsFactory($messageType))->create($to);
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
