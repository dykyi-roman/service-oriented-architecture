<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

final class InMemoryCache implements CacheInterface
{
    public array $data = [];

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value, $ttl = null): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function delete(array $keys): bool
    {
        foreach ($keys as $key) {
            unset($this->data[$key]);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        $this->data = [];

        return true;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
}
