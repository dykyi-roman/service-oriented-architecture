<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Auth\Service\Auth;
use App\Domain\Auth\Service\Registration;

final class AuthAdapter
{
    private Auth $auth;
    private Registration $registration;

    public function __construct(Auth $auth, Registration $registration)
    {
        $this->auth = $auth;
        $this->registration = $registration;
    }

    public function authorize(string $email, $password): array
    {
        return $this->auth->authorizeByEmail($email, $password);
    }

    public function registration(): array
    {
        return $this->registration->createNewUser();
    }
}
