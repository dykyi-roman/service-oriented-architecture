<?php

declare(strict_types=1);

namespace App\Domain\Message\Response;

interface ApiResponseInterface
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    public function isSuccess(): bool;

    public function getData(): array;

    public function hasError(): bool;

    public function getErrorMessage(): string;

    public function getErrorCode(): int;
}
