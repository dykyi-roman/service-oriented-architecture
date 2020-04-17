<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

interface CacheInterface
{
    public function get(string $key, $default = null);

    public function set(string $key, $value, $ttl = null): void;

    public function delete(array $keys): bool;

    public function clear(): bool;

    public function has(string $key): bool;
}
