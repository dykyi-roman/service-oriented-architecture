<?php

declare(strict_types=1);

namespace App\Domain\Message\ValueObject;

use App\Domain\Message\Exception\MessageException;

use function in_array;

final class Recipient
{
    public const TYPE_PHONE = 'phone';
    public const TYPE_EMAIL = 'email';

    public const ALLOW_MESSAGES_TYPE = [
        self::TYPE_EMAIL,
        self::TYPE_PHONE,
    ];

    private string $type;
    private string $value;

    /**
     * @throws MessageException
     */
    public function __construct(string $type, string $value)
    {
        $this->assertAvailableType($type);

        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @param string $type
     *
     * @throws MessageException
     */
    private function assertAvailableType(string $type): void
    {
        if (!in_array($type, self::ALLOW_MESSAGES_TYPE, true)) {
            throw MessageException::messageTypeIsNotSupport();
        }
    }

    public static function autoDetectType(string $contact): Recipient
    {
        $type = self::TYPE_PHONE;
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $type = self::TYPE_EMAIL;
        }

        return new self($type, $contact);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->value;
    }
}
