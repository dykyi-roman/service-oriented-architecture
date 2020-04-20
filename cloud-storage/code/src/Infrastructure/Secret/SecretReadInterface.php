<?php

declare(strict_types=1);

namespace App\Infrastructure\Secret;

interface SecretReadInterface
{
    public const KV_ENGINE = 'kv';

    public function read(string $key, string $engine = self::KV_ENGINE): array;
}
