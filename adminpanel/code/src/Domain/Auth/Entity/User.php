<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entity;

use stdClass;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
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

    public function __toString()
    {
        return '';
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

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
