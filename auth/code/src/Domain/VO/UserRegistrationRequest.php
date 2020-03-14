<?php

declare(strict_types=1);

namespace App\Domain\VO;

use Immutable\Exception\ImmutableObjectException;
use Immutable\ValueObject\Email;
use Immutable\ValueObject\ValueObject;

final class UserRegistrationRequest extends ValueObject
{
    protected string $email;
    protected string $password;
    protected string $phone;
    protected FullName $fullName;

    /**
     * @inheritDoc
     *
     * @throws ImmutableObjectException
     */
    public function __construct(string $email, string $password, string $phone, FullName $fullName)
    {
        $this->withChanged($email, $password, $phone, $fullName);
        parent::__construct();
    }

    /**
     * @inheritDoc
     *
     * @throws \InvalidArgumentException
     * @throws ImmutableObjectException
     */
    public function withChanged(
        string $email,
        string $password,
        string $phone,
        FullName $fullName
    ): ValueObject {
        try {
            new Email($email);
            new NotEmpty($password);
            new Phone($phone);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }

        return $this->with([
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'fullName' => $fullName,
        ]);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
