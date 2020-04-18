<?php

declare(strict_types=1);

namespace App\Application;

final class ResponseFactory
{
    public static function success(array $data = []): array
    {
        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    public static function error(string $errors): array
    {
        return [
            'status' => 'error',
            'errors' => $errors,
        ];
    }
}
