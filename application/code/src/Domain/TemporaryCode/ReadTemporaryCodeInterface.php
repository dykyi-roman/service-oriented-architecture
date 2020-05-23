<?php

declare(strict_types=1);

namespace App\Domain\TemporaryCode;

interface ReadTemporaryCodeInterface
{
    public function read(string $domain, string $key): string;
}
