<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Exception\AdapterException;

use function explode;

final class AdapterFactory
{
    private AvailableAdaptersFinder $availableAdapters;

    public function __construct(AvailableAdaptersFinder $availableAdapters)
    {
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
            $class = sprintf('%s\%sAdapter', $namespace, str_replace('-', '', ucwords($name, '-')));
            if (!class_exists($class)) {
                throw AdapterException::adapterIsNotSupport($name);
            }
            $handlers[$name] = new $class();
        }

        return $handlers;
    }
}
