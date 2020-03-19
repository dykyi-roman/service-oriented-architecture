<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

interface MessageInterface
{
    public function recipients(): Recipients;

    public function template(): Template;
}