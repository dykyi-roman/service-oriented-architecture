<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Immutable\ValueObject\ValueObject;

final class Email extends ValueObject
{
    protected string $email;

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function __construct(string $email)
    {
        $this->withChanged($email);
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function withChanged(string $email): ValueObject
    {
        new \Immutable\ValueObject\Email($email);
        return $this->with([
            'phone' => $email,
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
