<?php

declare(strict_types=1);

namespace App\Application\Common\Service;

final class ExceptionLogger
{
    /**
     * @param string|array $error
     */
    public static function log(string $method, $error): array
    {
        $tmp = explode('\\', $method);

        return [
            'Application',
            [
                'method' => end($tmp),
                'error' => $error,
            ],
        ];
    }
}
