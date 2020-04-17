<?php

declare(strict_types=1);

namespace App\Infrastructure\Metrics;

final class InMemoryMetrics implements MetricsInterface
{
    public function set(string $key, int $value, array $tags = []): void
    {
        // Mock method.
    }

    public function inc(string $key, int $sampleRate = 1, array $tags = []): void
    {
        // Mock method.
    }

    public function dec(string $key, int $sampleRate = 1, array $tags = []): void
    {
        // Mock method.
    }

    public function startTiming(string $key): void
    {
        // Mock method.
    }

    public function endTiming(string $key, float $sampleRate = 1.0, array $tags = []): void
    {
        // Mock method.
    }

    public function gauge(string $key, $value, array $tags = []): void
    {
        // Mock method.
    }
}
