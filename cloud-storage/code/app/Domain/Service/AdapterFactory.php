<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Exception\AdapterException;
use ArrayIterator;
use Psr\Log\LoggerInterface;

use function explode;

final class AdapterFactory
{
    private LoggerInterface $logger;
    private AvailableAdaptersFinder $availableAdapters;

    public function __construct(AvailableAdaptersFinder $availableAdapters, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->availableAdapters = $availableAdapters;
    }

    public function create(string $adapters): iterable
    {
        $names = explode(',', $adapters);

        if (0 === count($names)) {
            throw AdapterException::adapterListIsEmpty($this->availableAdapters->supported());
        }

        $handlers = new ArrayIterator();
        foreach ($names as $name) {
            $className = sprintf('App\Infrastructure\\Adapters\\%sAdapter', $name);
            $handlers[] = new $className($this->logger);
        }

        return $handlers;
    }
}
