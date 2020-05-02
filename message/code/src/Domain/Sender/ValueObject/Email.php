<?php

declare(strict_types=1);

namespace App\Domain\Sender\ValueObject;

use Immutable\ValueObject\ValueObject;

final class Email extends ValueObject
{
    protected string $email;

    /**
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function __construct(string $email)
    {
        $this->withChanged($email);
        parent::__construct();
    }

    /**
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function withChanged(string $email): ValueObject
    {
        new \Immutable\ValueObject\Email($email);
        return $this->with([
            'email' => $email,
        ]);
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->email;
    }
}
