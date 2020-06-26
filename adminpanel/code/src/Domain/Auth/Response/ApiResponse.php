<?php

declare(strict_types=1);

namespace App\Domain\Auth\Response;

final class ApiResponse implements ApiResponseInterface
{
    private int $errorCode;
    private string $status;
    private string $errorMessage;
    private array $data;

    public function __construct(array $payload)
    {
        $this->status = $payload['status'];
        if ($this->isSuccess()) {
            $this->errorCode = 0;
            $this->data = $payload['data'];
        }

        if ($this->hasError()) {
            $this->errorCode = (int) $payload['code'];
            $this->errorMessage = (string) $payload['error'];
        }
    }

    public static function withError(string $errorMessage, int $errorCode = 0): ApiResponse
    {
        return new self(
            [
                'status' => ApiResponseInterface::STATUS_ERROR,
                'error' => $errorMessage,
                'code' => $errorCode,
            ]
        );
    }

    public static function withSuccess(array $data): ApiResponse
    {
        return new self(
            [
                'status' => ApiResponseInterface::STATUS_ERROR,
                'data' => $data,
            ]
        );
    }

    public function isSuccess(): bool
    {
        return $this->status === ApiResponseInterface::STATUS_SUCCESS;
    }

    public function hasError(): bool
    {
        return $this->status === ApiResponseInterface::STATUS_ERROR;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
