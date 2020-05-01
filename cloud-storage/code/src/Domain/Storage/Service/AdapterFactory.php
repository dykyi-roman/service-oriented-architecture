<?php

declare(strict_types=1);

namespace App\Domain\Storage\Service;

use App\Domain\Storage\Exception\AdapterException;

use function explode;

final class AdapterFactory
{
    private ExistAdaptersFinder $existAdapters;

    public function __construct(ExistAdaptersFinder $existAdapters)
    {
        $this->existAdapters = $existAdapters;
    }

    /**
     * @inheritDoc
     * @throws AdapterException
     */
    public function create(string $adapters): array
    {
        if ('' === $adapters) {
            throw AdapterException::adapterListIsEmpty($this->existAdapters->supported());
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
