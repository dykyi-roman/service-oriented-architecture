<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Command;

use App\Application\Auth\Request\SignUpRequest;

/**
 * @see SignUpHandler::class
 */
final class SignUpCommand
{
    private SignUpRequest $request;

    public function __construct(SignUpRequest $request)
    {
        $this->request = $request;
    }

    public function request(): SignUpRequest
    {
        return $this->request;
    }
}
