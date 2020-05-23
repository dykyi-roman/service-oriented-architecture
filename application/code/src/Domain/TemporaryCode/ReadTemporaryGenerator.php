<?php

declare(strict_types=1);

namespace App\Domain\TemporaryCode;

use App\Infrastructure\Cache\CacheInterface;

final class ReadTemporaryGenerator implements ReadTemporaryCodeInterface
{
    private const TEMPORARY_CODE_KEY = 'temporary_code_%s_%s';

    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function read(string $domain, string $key): string
    {
        return $this->cache->get(sprintf(self::TEMPORARY_CODE_KEY, $domain, $key));
    }
}
