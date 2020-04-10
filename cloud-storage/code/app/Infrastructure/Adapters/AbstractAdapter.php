<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

abstract class AbstractAdapter
{
    public function name(): string
    {
        return str_replace('Adapter', '', substr(strrchr(static::ADAPTER, "\\"), 1));
    }
}
