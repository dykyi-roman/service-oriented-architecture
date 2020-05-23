<?php

declare(strict_types=1);

namespace App\Domain\TemporaryCode;

interface  WriteTemporaryCodeInterface
{
    public const TEMPORARY_CODE_TTL = 60;

    public function generate(string $domain, string $key): string;
}
