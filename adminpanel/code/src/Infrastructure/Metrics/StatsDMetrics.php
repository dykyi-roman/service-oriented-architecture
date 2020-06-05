<?php

declare(strict_types=1);

namespace App\Infrastructure\Metrics;

use Domnikl\Statsd\Client;
use Domnikl\Statsd\Connection\UdpSocket;

final class StatsDMetrics implements MetricsInterface
{
    private const COUNTER_POSTFIX = '_count';

    private const TIMING_POSTFIX = '_timing';

    private Client $client;

    public function __construct(string $host, int $port, string $namespace, int $timeout = 3)
    {
        $this->client = new Client(new UdpSocket($host, $port, $timeout), $namespace);
    }

    public function set(string $key, int $value, array $tags = []): void
    {
        $this->client->set($key, $value, $tags);
    }

    public function inc(string $key, int $sampleRate = 1, array $tags = []): void
    {
        $this->client->increment($key . self::COUNTER_POSTFIX, $sampleRate, $tags);
    }

    public function dec(string $key, int $sampleRate = 1, array $tags = []): void
    {
        $this->client->decrement($key . self::COUNTER_POSTFIX, $sampleRate, $tags);
    }

    public function startTiming(string $key): void
    {
        $this->client->startTiming($key . self::TIMING_POSTFIX);
    }

    public function endTiming(string $key, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->client->endTiming($key . self::TIMING_POSTFIX, $sampleRate, $tags);
    }

    public function gauge(string $key, $value, array $tags = []): void
    {
        $this->client->gauge($key, $value, $tags);
    }
}
