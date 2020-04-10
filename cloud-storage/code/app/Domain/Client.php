<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\FileStorageException;
use App\Domain\Service\AdapterFactory;

final class Client
{
    private bool $state = false;

    /**
     * @var FileStorageInterface[]|array
     */
    private iterable $adapters;
    private FileStorageInterface $reserveAdapter;
    private AdapterFactory $adapterFactory;

    public function __construct(AdapterFactory $adapterFactory)
    {
        $this->adapterFactory = $adapterFactory;
    }

    public function init(string $adapters, string $reserveAdapter = null): void
    {
        $this->adapters = $this->adapterFactory->create($adapters);
        if (null !== $reserveAdapter) {
            $this->reserveAdapter = $this->adapterFactory->create($reserveAdapter)->current();
        }

        $this->state = true;
    }

    public function createFolder(string $name): void
    {
        $this->execute(__FUNCTION__, [$name]);
    }

    public function delete(string $path): void
    {
        $this->execute(__FUNCTION__, [$path]);
    }

    public function execute(string $method, $params): void
    {
        $this->assertConnectStateCheck();
        foreach ($this->adapters as $adapter) {
            $adapter->{$method}(...$params);
        }
    }

    private function assertConnectStateCheck(): void
    {
        if (!$this->state) {
            throw FileStorageException::connectProblem();
        }
    }
}
