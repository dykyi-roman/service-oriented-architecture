<?php

declare(strict_types=1);

namespace App\Domain\VO;

use Immutable\Exception\ImmutableObjectException;
use Immutable\ValueObject\Email;
use Immutable\ValueObject\ValueObject;

final class UserRegistrationRequest extends ValueObject
{
    protected Email $email;
    protected string $password;
    protected Phone $phone;
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
            new NotEmpty($password);

            return $this->with([
                'email' => new Email($email),
                'phone' => new Phone($phone),
                'password' => $password,
                'fullName' => $fullName,
            ]);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): Phone
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
