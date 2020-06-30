<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiResponseInterface;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\FullName;
use App\Domain\Auth\ValueObject\Password;
use App\Domain\Auth\ValueObject\Phone;

class AuthAdapter
{
    private Auth $auth;
    private SignUp $signUp;

    public function __construct(Auth $auth, SignUp $signUp)
    {
        $this->auth = $auth;
        $this->signUp = $signUp;
    }

    public function authorize(Email $email, Password $password): ApiResponseInterface
    {
        $response = $this->auth->authorizeByEmail($email, $password);
        if ($response->hasError()) {
            throw AuthException::invalidCredentials($response->getErrorMessage());
        }

        return $response;
    }

    public function signUp(Email $email, Password $password, Phone $phone, FullName $fullName): ApiResponseInterface
    {
        $response = $this->signUp->createNewUser($email, $password, $phone, $fullName);
        if ($response->hasError()) {
            throw AuthException::unexpectedErrorInSignUpProcess($response->getErrorMessage());
        }

        return $response;
    }

    public function passwordRestore(string $contact, Password $password): void
    {
        $response = $this->auth->passwordRestore($contact, $password);
        if ($response->hasError()) {
            throw AuthException::changePasswordError($response->getErrorMessage());
        }
    }
}
