<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiResponseInterface;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\FullName;
use App\Domain\Auth\ValueObject\Password;

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

    public function signUp(Email $email, Password $password, FullName $fullName): ApiResponseInterface
    {
        $response = $this->signUp->createNewUser($email, $password, $fullName);
        if ($response->hasError()) {
            throw AuthException::createNewUserError($response->getErrorMessage());
        }

        return $response;
    }

    public function getAllUsers(): ApiResponseInterface
    {
        $response = $this->signUp->getAllUsers();
        if ($response->hasError()) {
            throw AuthException::getAllUserError($response->getErrorMessage());
        }

        return $response;
    }

    public function getUser(string $id): ApiResponseInterface
    {
        $response = $this->signUp->getUserById($id);
        if ($response->hasError()) {
            throw AuthException::getUserError($id, $response->getErrorMessage());
        }

        return $response;
    }

    public function updateUser(string $id, array $data): ApiResponseInterface
    {
        $response = $this->signUp->updateUserById($id, $data);
        if ($response->hasError()) {
            throw AuthException::getUserError($id, $response->getErrorMessage());
        }

        return $response;
    }
}
