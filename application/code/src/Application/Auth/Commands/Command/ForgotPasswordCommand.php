<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Command;

use App\Domain\Message\Exception\MessageException;

/**
 * @see ForgotPasswordHandler::class
 */
final class ForgotPasswordCommand
{
    private string $contact;

    /**
     * @throws MessageException
     */
    public function __construct(string $contact)
    {
        $this->contact = $contact;
    }

    public function contact(): string
    {
        return $this->contact;
    }
}
