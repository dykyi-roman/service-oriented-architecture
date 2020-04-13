<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Exception\AdapterException;
use App\Domain\Exception\StorageConnectException;
use App\Domain\StorageAdapterInterface;
use App\Domain\ValueObject\UploadFile;
use App\Infrastructure\Metrics\MetricsInterface;
use DomainException;
use Psr\Log\LoggerInterface;
use Throwable;

final class Client
{
    private bool $state = false;

    private array $adapters;
    private LoggerInterface $logger;
    private AdapterFactory $adapterFactory;
    private MetricsInterface $metrics;

    public function __construct(AdapterFactory $adapterFactory, MetricsInterface $metrics, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->adapterFactory = $adapterFactory;
        $this->metrics = $metrics;
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

    public function upload(UploadFile $uploadFile): array
    {
        return $this->execute(__FUNCTION__, [$uploadFile]);
    }

    public function download(string $filePath): array
    {
        return $this->execute(__FUNCTION__, [$filePath]);
    }

    public function execute(string $method, array $params): array
    {
        $this->assertConnectStateCheck();

        $response = [];
        foreach ($this->adapters as $adapter => $object) {
            $this->metrics->startTiming('api_cloud_request');
            $payload = $this->doExecute($object, $adapter, $method, $params);
            $this->metrics->endTiming('api_cloud_request', 1.0, ['adapter' => $adapter, 'method' => $method]);
            $response[] = ['adapter' => $adapter, 'payload' => $payload];
        }

        return $response;
    }

    private function doExecute(
        StorageAdapterInterface $adapter,
        string $adapterName,
        string $method,
        array $params
    ): array {
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
