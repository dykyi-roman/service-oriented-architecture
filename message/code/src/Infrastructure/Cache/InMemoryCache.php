<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

final class InMemoryCache implements CacheInterface
{
    public array $data = [];

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null): bool
    {
        $this->data[$key] = $value;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete($key): bool
    {
        unset($this->data[$key]);

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
    public function getMultiple($keys, $default = null): iterable
    {
        // TODO: Implement getMultiple() method.

        return new \ArrayIterator($this->data);
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null): bool
    {
        // TODO: Implement setMultiple() method.
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys): bool
    {
        // TODO: Implement deleteMultiple() method.
        return true;
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        return isset($this->data[$key]);
    }
}
