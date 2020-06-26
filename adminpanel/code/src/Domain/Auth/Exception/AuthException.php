<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exception;

use RuntimeException;

class AuthException extends RuntimeException
{
    public static function unexpectedErrorInAuthoriseProcess(string $message): self
    {
        return new static(sprintf('Error in Authorise process. Error: %s', $message), 2101);
    }

    public static function createNewUserError(string $message): self
    {
        return new static(sprintf('Error in Sign up process. Error: %s', $message), 2102);
    }

    public static function invalidCredentials(string $message): self
    {
        return new static($message, 2103);
    }

    public static function publicKeyIsNotFound(string $key): self
    {
        return new static(sprintf('Public key is not found by path %s', $key), 2104);
    }

    public static function publicKeyIsNotUpdated(string $message): self
    {
        return new static(sprintf('Could not download a new public key. Reason: %s', $message), 2105);
    }

    public static function tokenIsExpired(): self
    {
        return new static('Token is expired', 2106);
    }

    public static function tokenIsNotDecoded(): self
    {
        return new static('Could not extract payload from token', 2107);
    }

    public static function tokenIsNotFoundInCookies(string $token): self
    {
        return new static(sprintf('Could not found "%s" token in the Cookies', $token), 2109);
    }

    public static function invalidEmailFormat(): self
    {
        return new static('Invalid email format', 2111);
    }

    public static function passwordLengthIsTooShort(int $length): self
    {
        return new static(sprintf('Your password is not secure. Min length should be "%d" symbol', $length), 2112);
    }

    public static function tokenIsBroken(): self
    {
        return new static('Token is broken', 2113);
    }

    public static function getAllUserError(string $message): self
    {
        return new static(sprintf('Error when get users list. Error: %s', $message), 2114);
    }

    public static function getUserError(string $id, string $message): self
    {
        return new static(sprintf('Error when get user "%s". Error: %s', $id, $message), 2115);
    }

    public static function updateUserError(string $id, string $message): self
    {
        return new static(sprintf('Error when update user "%s". Error: %s', $id, $message), 2116);
    }
}
