<?php

declare(strict_types=1);

namespace App\Domain\Sender;

use App\Domain\Sender\ValueObject\Recipients;
use App\Domain\Template\ValueObject\Template;

interface MessageInterface
{
    public function recipients(): Recipients;

    public function template(): Template;
}