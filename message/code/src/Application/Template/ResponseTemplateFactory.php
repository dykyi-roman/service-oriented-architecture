<?php

declare(strict_types=1);

namespace App\Application\Template;

final class ResponseTemplateFactory
{
    public static function success(array $data): array
    {
        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    public static function error(string $error): array
    {
        return [
            'status' => 'error',
            'error' => $error,
        ];
    }
}
