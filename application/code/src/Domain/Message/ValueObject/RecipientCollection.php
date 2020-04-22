<?php

declare(strict_types=1);

namespace App\Domain\Message\ValueObject;

use function in_array;
use App\Domain\Message\Exception\MessageException;

final class RecipientCollection
{
    /** @var Recipient[] */
    private array $collection = [];

    public function add(Recipient $recipient): self
    {
        $this->collection[$recipient->type()] = $recipient->value();

        return $this;
    }

    public function get(): array
    {
        return $this->collection;
    }
}
