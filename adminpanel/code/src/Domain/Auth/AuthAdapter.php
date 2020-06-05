<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Service\Auth;
use App\Domain\Auth\Service\SignUp;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\FullName;
use App\Domain\Auth\ValueObject\Password;
use App\Domain\Auth\ValueObject\Token;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class AuthAdapter
{
    private Auth $auth;
    private SignUp $signUp;

    public function __construct(Auth $auth, SignUp $signUp)
    {
        $this->auth = $auth;
        $this->signUp = $signUp;
    }

    public function authorize(Email $email, Password $password): Token
    {
        $response = $this->auth->authorizeByEmail($email, $password);

        return new Token($response);
    }

    public function signUp(Email $email, Password $password, FullName $fullName): UuidInterface
    {
        $response = $this->signUp->createNewUser($email, $password, $fullName);
        if ($response['status'] === 'error') {
            throw AuthException::unexpectedErrorInSignUpProcess($response['error']);
        }

        return Uuid::fromString($response['data']['uuid']);
    }
}
