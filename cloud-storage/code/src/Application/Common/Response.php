<?php

declare(strict_types=1);

namespace App\Application\Common;

final class Response
{
    public static function success(array $data): array
    {
        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    public static function error(Error $error): array
    {
        return [
            'status' => 'error',
            'code' => $error->code(),
            'error' => $error->message(),
        ];
    }
}
