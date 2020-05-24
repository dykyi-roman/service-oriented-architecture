<?php

declare(strict_types=1);

namespace App\Domain\TemporaryCode;

use App\Infrastructure\Cache\CacheInterface;

final class WriteTemporaryCode implements WriteTemporaryCodeInterface
{
    private const CODE_LENGTH = 5;
    private const TEMPORARY_CODE_TTL = 60;
    private const TEMPORARY_CODE_KEY = 'temporary_code_%s_%s';

    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function generate(string $domain, string $key): string
    {
        $code = $this->randomCode(self::CODE_LENGTH);
        $this->cache->set(sprintf(self::TEMPORARY_CODE_KEY, $domain, $key), $code, self::TEMPORARY_CODE_TTL);

        return $code;
    }

    private function randomCode(int $length): string
    {
        return substr(md5(uniqid((string) mt_rand(), true)), 0, $length);
    }
}
