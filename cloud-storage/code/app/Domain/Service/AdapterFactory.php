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

    /**
     * @inheritDoc
     * @throws AdapterException
     */
    public function create(string $adapters): array
    {
        if ('' === $adapters) {
            throw AdapterException::adapterListIsEmpty($this->availableAdapters->supported());
        }
        $names = explode(',', trim($adapters));

        $handlers = [];
        $namespace = 'App\\Infrastructure\\Adapters';
        foreach ($names as $name) {
            $class = sprintf('%s\%sAdapter', $namespace, $name);
            if (!class_exists($class)) {
                throw AdapterException::adapterIsNotSupport($name);
            }
            $handlers[$name] = new $class($this->logger);
        }

        return $handlers;
    }
}
