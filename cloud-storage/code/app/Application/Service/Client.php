<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Exception\AdapterException;
use App\Domain\Exception\StorageConnectException;
use App\Domain\Service\AdapterFactory;
use App\Domain\StorageInterface;
use App\Domain\ValueObject\StorageResponse;
use App\Domain\ValueObject\UploadFile;
use App\Infrastructure\Cache\CacheInterface;
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
    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    public function __construct(
        AdapterFactory $adapterFactory,
        MetricsInterface $metrics,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->adapterFactory = $adapterFactory;
        $this->metrics = $metrics;
        $this->cache = $cache;
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
        $key = base64_encode($filePath);
        if (!$this->cache->has($key)) {
            $result = $this->execute(__FUNCTION__, [$filePath]);
            $this->cache->set($key, json_encode($result, JSON_THROW_ON_ERROR, 512), env('CACHE_TTL'));
        }

        return json_decode($this->cache->get($key), true, 512, JSON_THROW_ON_ERROR);
    }

    public function execute(string $method, array $args): array
    {
        $this->assertConnectStateCheck();

        $response = [];
        foreach ($this->adapters as $adapter => $storage) {
            $this->metrics->startTiming('api_cloud_request');
            $payload = $this->doExecute($storage, $adapter, $method, $args);
            $this->metrics->endTiming('api_cloud_request', 1.0, ['adapter' => $adapter, 'method' => $method]);
            $response[] = ['adapter' => $adapter, 'payload' => $payload->toArray()];
        }

        return $response;
    }

    private function doExecute(StorageInterface $storage, string $adapter, string $method, array $args): StorageResponse
    {
        try {
            return $storage->{$method}(...$args);
        } catch (Throwable $exception) {
            $this->logger->error('CloudStorage', [
                'adapter' => $adapter,
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
