<?php

declare(strict_types=1);

namespace App\Domain\Sender\ValueObject;

use Immutable\ValueObject\ValueObject;
use App\Domain\Sender\Exception\MessageException;

final class MessageType extends ValueObject
{
    public const TYPE_PHONE = 'phone';
    public const TYPE_EMAIL = 'email';

    public const ALLOW_MESSAGES_TYPE = [
        self::TYPE_EMAIL,
        self::TYPE_PHONE,
    ];

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
        if (!\in_array($type, self::ALLOW_MESSAGES_TYPE, true)) {
            throw MessageException::messageTypeIsNotSupport();
        }
    }
}
