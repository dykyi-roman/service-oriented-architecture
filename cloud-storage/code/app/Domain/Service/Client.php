<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Exception\AdapterException;
use App\Domain\Exception\StorageConnectException;
use App\Domain\StorageAdapterInterface;
use DomainException;
use Psr\Log\LoggerInterface;
use Throwable;

final class Client
{
    private bool $state = false;

    private array $adapters;
    private LoggerInterface $logger;
    private AdapterFactory $adapterFactory;

    public function __construct(AdapterFactory $adapterFactory, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->adapterFactory = $adapterFactory;
    }

    /**
     * @param string $adapters
     *
     * @throws AdapterException
     */
    public function init(string $adapters): void
    {
        $this->adapters = $this->adapterFactory->create($adapters);
        $this->state = true;
    }

    public function createFolder(string $name): array
    {
        return $this->execute(__FUNCTION__, [$name]);
    }

    public function delete(string $path): array
    {
        return $this->execute(__FUNCTION__, [$path]);
    }

    public function upload(string $filePath, string $uploadFileDir, string $uploadFileExt): array
    {
        return $this->execute(__FUNCTION__, [$filePath, $uploadFileDir, $uploadFileExt]);
    }

    public function download(string $filePath, ?string $downloadFilePath): array
    {
        return $this->execute(__FUNCTION__, [$filePath, $downloadFilePath]);
    }

    public function execute(string $method, array $params): array
    {
        $this->assertConnectStateCheck();

        $response = [];
        foreach ($this->adapters as $adapterName => $adapterObject) {
            $response[] = [
                'adapter' => $adapterName,
                'payload' => $this->doExecute($adapterObject, $adapterName, $method, $params)
            ];
        }

        return $response;
    }

    private function doExecute(StorageAdapterInterface $adapter, string $adapterName, string $method, array $params)
    {
        try {
            return $adapter->{$method}(...$params);
        } catch (Throwable $exception) {
            $this->logger->error('CloudStorage', [
                'adapter' => $adapterName,
                'method' => $method,
                'error' => $exception->getMessage()
            ]);

            throw new DomainException($exception->getMessage());
        }
    }

    /**
     * @throws StorageConnectException
     */
    private function assertConnectStateCheck(): void
    {
        if (!$this->state) {
            throw StorageConnectException::connectProblem();
        }
    }
}
