<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use App\Domain\User\ValueObject\FullName;
use App\Domain\User\ValueObject\NotEmpty;
use App\Domain\User\ValueObject\Phone;
use Immutable\Exception\ImmutableObjectException;
use Immutable\ValueObject\Email;
use Immutable\ValueObject\ValueObject;
use InvalidArgumentException;
use Throwable;

final class UserRegistrationRequest extends ValueObject
{
    protected string $password;
    protected Email $email;
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
     * @throws InvalidArgumentException
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
        } catch (Throwable $exception) {
            throw new InvalidArgumentException($exception->getMessage());
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
