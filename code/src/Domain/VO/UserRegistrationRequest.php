<?php

declare(strict_types=1);

namespace App\Domain\VO;

use Immutable\Exception\ImmutableObjectException;
use Immutable\ValueObject\Email;
use Immutable\ValueObject\ValueObject;

final class UserRegistrationRequest extends ValueObject
{
    protected string $email;
    protected string $username;
    protected string $password;

    /**
     * UserRegistrationRequest constructor.
     *
     * @param string $email
     * @param string $password
     * @param string $username
     *
     * @throws ImmutableObjectException
     */
    public function __construct(string $email, string $password, string $username)
    {
        $this->withChanged($email, $password, $username);
        parent::__construct();
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $username
     *
     * @return UserRegistrationRequest|ValueObject
     *
     * @throws \InvalidArgumentException
     * @throws ImmutableObjectException
     */
    public function withChanged(
        string $email,
        string $password,
        string $username
    ): ValueObject {
        try {
            new NotEmpty($username);
            new NotEmpty($password);
            new Email($email);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }

        return $this->with([
            'email' => $email,
            'password' => $password,
            'username' => $username,
        ]);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
