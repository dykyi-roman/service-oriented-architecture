<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use Psr\SimpleCache\CacheInterface;

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
    public function set($key, $value, $ttl = null)
    {
        $this->data[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        unset($this->data[$key]);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {
        // TODO: Implement getMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {
        // TODO: Implement deleteMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
}