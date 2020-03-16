<?php

declare(strict_types=1);

namespace App\Domain\VO;

use Immutable\ValueObject\ValueObject;

final class FullName extends ValueObject
{
    protected string $firstName;

    protected string $lastName;

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function __construct(string $firstName, string $lastName)
    {
        $this->withChanged($firstName, $lastName);
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function withChanged(string $firstName, string $lastName): ValueObject
    {
        try {
            new NotEmpty($firstName);
            new NotEmpty($lastName);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }

        return $this->with([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function toString(bool $lastFirst = true): string
    {
        $first = $lastFirst ? $this->lastName : $this->firstName;
        $second = $lastFirst ? $this->firstName : $this->lastName;

        return sprintf('%s %s', $first, $second);
    }
}
