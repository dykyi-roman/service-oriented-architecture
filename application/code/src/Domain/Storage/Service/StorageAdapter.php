<?php

declare(strict_types=1);

namespace App\Domain\Storage\Service;

use App\Domain\Storage\Exception\StorageException;
use App\Domain\Storage\Response\ApiResponse;
use App\Domain\Storage\Response\ApiResponseInterface;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Throwable;

final class StorageAdapter
{
    private string $host;
    private const UPLOAD_URI = '/api/storage/upload';
    private const DOWNLOAD_URI = '/api/storage/download';

    private ClientInterface $client;
    private ResponseDataExtractorInterface $responseDataExtractor;

    public function __construct(
        ClientInterface $client,
        ResponseDataExtractorInterface $responseDataExtractor,
        string $host
    ) {
        $this->host = $host;
        $this->client = $client;
        $this->responseDataExtractor = $responseDataExtractor;
    }

    /**
     * @throws StorageException
     */
    public function uploadFile(string $file, string $fileExt, string $dir = null): ApiResponseInterface
    {
        try {
            $dirData = [
                'name' => 'dir',
                'contents' => $dir
            ];

            $fileData = [
                'name' => 'file',
                'contents' => fopen($file, 'rb'),
                'filename' => 'name.' . $fileExt
            ];

            $elements = [$fileData];
            if ($dir !== null) {
                $elements[] = $dirData;
            }

            $stream = new MultipartStream($elements);
            $headers = ['Content-Type' => 'multipart/form-data; boundary=' . $stream->getBoundary()];
            $request = new Request('POST', $this->host . self::UPLOAD_URI, $headers, $stream);
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw StorageException::uploadProblem($exception->getMessage());
        }
    }

    /**
     * @throws StorageException
     */
    public function downloadFile(string $file): ApiResponseInterface
    {
        try {
            $parameters = json_encode(['file' => $file]);
            $request = $request = new Request('GET', $this->host . self::DOWNLOAD_URI, [], $parameters);
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw StorageException::downloadProblem($exception->getMessage());
        }
    }
}
