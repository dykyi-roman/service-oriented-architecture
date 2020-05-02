<?php

declare(strict_types=1);

namespace App\Application\Common;

final class Error
{
    private int $code;

    private string $message;

    private function __construct(string $message, int $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public static function create(string $message, int $code = 0): self
    {
        return new self($message, $code);
    }

    public function code(): int
    {
        return $this->code;
    }

    public function message(): string
    {
        return $this->message;
    }
}
