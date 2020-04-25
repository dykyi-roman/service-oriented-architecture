<?php

declare(strict_types=1);

namespace App\Infrastructure\Metrics;

interface MetricsInterface
{
    public function set(string $key, int $value, array $tags = []): void;

    public function inc(string $key, int $sampleRate = 1, array $tags = []): void;

    public function dec(string $key, int $sampleRate = 1, array $tags = []): void;

    public function startTiming(string $key): void;

    public function endTiming(string $key, float $sampleRate = 1.0, array $tags = []): void;

    public function gauge(string $key, $value, array $tags = []): void;
}
