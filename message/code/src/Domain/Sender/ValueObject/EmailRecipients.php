<?php

declare(strict_types=1);

namespace App\Domain\Sender\ValueObject;

use Immutable\ValueObject\ValueObject;

final class EmailRecipients extends ValueObject implements Recipients
{
    protected Email $sender;
    protected Email $recipient;

    /**
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function __construct(string $sender, string $recipient)
    {
        $this->withChanged($sender, $recipient);
        parent::__construct();
    }

    /**
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function withChanged(string $sender, string $recipient): ValueObject
    {
        return $this->with([
            'sender' => new Email($sender),
            'recipient' => new Email($recipient),
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
