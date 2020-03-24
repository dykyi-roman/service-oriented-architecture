<?php

declare(strict_types=1);

namespace App\Domain\Sender\ValueObject;

use Immutable\ValueObject\ValueObject;

final class PhoneRecipients extends ValueObject implements Recipients
{
    protected Phone $sender;
    protected Phone $recipient;

    /**
     * @inheritDoc
     * @throws \InvalidArgumentException
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function __construct(string $sender, string $recipient)
    {
        $this->withChanged($sender, $recipient);
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws \InvalidArgumentException
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function withChanged(string $sender, string $recipient): ValueObject
    {
        return $this->with([
            'sender' => new Phone($sender),
            'recipient' => new Phone($recipient),
        ]);
    }

    public function sender()
    {
        return $this->sender;
    }

    public function recipient()
    {
        return $this->recipient;
    }
}
