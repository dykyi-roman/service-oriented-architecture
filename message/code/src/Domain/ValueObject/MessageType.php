<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\MessageException;
use Immutable\ValueObject\ValueObject;

final class MessageType extends ValueObject
{
    private const TYPE_PHONE = 'phone';
    private const TYPE_EMAIL = 'email';

    protected string $type;

    /**
     * @inheritDoc
     *
     * @throws MessageException
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function __construct(string $type)
    {
        $this->withChanged($type);
        parent::__construct();
    }

    /**
     * @inheritDoc
     *
     * @throws MessageException
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function withChanged(string $type): ValueObject
    {
        new NotEmpty($type);
        $this->assertAvailableType($type);

        return $this->with([
            'type' => $type,
        ]);
    }

    public function toString(): string
    {
        return $this->type;
    }

    public function isPhone(): bool
    {
        return $this->type === self::TYPE_PHONE;
    }

    public function isEmail(): bool
    {
        return $this->type === self::TYPE_EMAIL;
    }

    /**
     * @param string $type
     *
     * @throws MessageException
     */
    private function assertAvailableType(string $type): void
    {
        if (!\in_array($type, [self::TYPE_EMAIL, self::TYPE_PHONE], true)) {
            throw MessageException::notSupportMessageType();
        }
    }
}
