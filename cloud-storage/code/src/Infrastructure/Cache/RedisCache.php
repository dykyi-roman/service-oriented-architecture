<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use Predis\Client;

final class RedisCache implements CacheInterface
{
    private Client $client;

    public function __construct(string $host, string $port)
    {
        $this->client = new Client([
            'host' => $host,
            'port' => $port,
        ]);

        $this->client->connect();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return '' === $this->client->get($key) ? $default : $this->client->get($key);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value, $ttl = null): void
    {
        $this->client->setEx($key, $ttl, $value);
    }

    /**
     * @inheritDoc
     */
    public function delete(array $keys): bool
    {
        return (bool)$this->client->del($keys);
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        $this->client->flushall();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return (bool)$this->client->get($key);
    }
}
