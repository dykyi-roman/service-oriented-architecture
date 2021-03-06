<?php

declare(strict_types=1);

namespace App\Domain\Sender\Exception;

class RecipientsException extends \InvalidArgumentException
{
    public static function recipientInNotFound(string $name): self
    {
        return new static(sprintf('Recipient "%s" is not found', $name), 4204);
    }
}
