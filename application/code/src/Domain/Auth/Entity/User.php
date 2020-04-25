<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entity;

use stdClass;

final class User
{
    private const JWT_PAYLOAD_PROPERTIES = ['id', 'email', 'phone'];

    public string $id;
    public string $phone;
    public string $email;

    public static function createUserByJWTPayload(stdClass $payload): self
    {
        return static::setPropertyIfExist(new self(), $payload, self::JWT_PAYLOAD_PROPERTIES);
    }

    private static function setPropertyIfExist(User $user, stdClass $payload, array $properties): User
    {
        foreach ($properties as $property) {
            if (property_exists($payload, $property)) {
                $user->{$property} = $payload->{$property};
            }
        }

        return $user;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
