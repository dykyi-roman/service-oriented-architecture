<?php

declare(strict_types=1);

namespace App\Domain\Storage;

use App\Domain\Storage\Exception\StorageException;
use App\Domain\Storage\Response\ApiResponse;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use Psr\Http\Client\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Throwable;

final class StorageAdapter
{
    public const STORAGE_URI = '/api/admin/user/%s/storage/files';

    private ClientInterface $client;
    private ResponseDataExtractorInterface $responseDataExtractor;
    private string $host;

    public function __construct(
        ClientInterface $client,
        ResponseDataExtractorInterface $responseDataExtractor,
        string $host
    ) {
        $this->host = $host;
        $this->client = $client;
        $this->responseDataExtractor = $responseDataExtractor;
    }

    public function searchFilesByUserId(string $userId): ApiResponse
    {
        try {
            $request = new Request('GET', $this->host . sprintf(self::STORAGE_URI, $userId));
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw StorageException::couldNotFindAnyFiles($userId);
        }
    }
}
