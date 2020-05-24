<?php

declare(strict_types=1);

namespace App\Domain\TemporaryCode;

interface WriteTemporaryCodeInterface
{
    public function generate(string $domain, string $key): string;
}
