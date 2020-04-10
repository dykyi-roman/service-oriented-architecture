<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\FileStorageException;
use App\Domain\Service\AdapterFactory;
use App\Infrastructure\Adapters\StorageAdapterInterface;

final class Client
{
    private bool $state = false;

    /**
     * @var StorageAdapterInterface[]|array
     */
    private iterable $adapters;
    private StorageAdapterInterface $reserveAdapter;
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

    public function upload(string $filePath, string $uploadFilePath): void
    {
        $this->execute(__FUNCTION__, [$filePath, $uploadFilePath]);
    }

    public function download(string $filePath, ?string $downloadFilePath): array
    {
        return $this->execute(__FUNCTION__, [$filePath, $downloadFilePath]);
    }

    public function execute(string $method, $params): array
    {
        $response = [];
        $this->assertConnectStateCheck();
        foreach ($this->adapters as $adapter) {
            $response[$adapter->name()] = $adapter->{$method}(...$params);
        }
        return $response;
    }

    private function assertConnectStateCheck(): void
    {
        if (!$this->state) {
            throw FileStorageException::connectProblem();
        }
    }
}
