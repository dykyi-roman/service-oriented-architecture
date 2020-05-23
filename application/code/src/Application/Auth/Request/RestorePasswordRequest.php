<?php

declare(strict_types=1);

namespace App\Application\Auth\Request;

use App\Application\Auth\Exception\AppAuthException;
use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\ValueObject\Password;

final class RestorePasswordRequest
{
    private string $contact;
    private string $code;
    private Password $password;

    public function __construct(string $contact, string $code, string $password)
    {
        $this->assertWhenEmptyFields([$contact, $code, $password]);
        try {
            $this->contact = $contact;
            $this->code = $code;
            $this->password = new Password($password);
        } catch (AuthException $exception) {
            throw AppAuthException::domainException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @throws AppAuthException
     */
    private function assertWhenEmptyFields(array $fields): void
    {
        foreach ($fields as $field) {
            if ('' === $field) {
                throw AppAuthException::requireFieldsIsEmpty();
            }
        }
    }

    public function code(): string
    {
        return $this->code;
    }

    public function contact(): string
    {
        return $this->contact;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
