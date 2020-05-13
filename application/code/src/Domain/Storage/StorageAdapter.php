<?php

declare(strict_types=1);

namespace App\Domain\Storage;

use App\Domain\Storage\Exception\StorageException;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
        ParameterBagInterface $bag,
        ResponseDataExtractorInterface $responseDataExtractor
    ) {
        $this->host = $bag->get('STORAGE_SERVICE_HOST');
        $this->client = $client;
        $this->responseDataExtractor = $responseDataExtractor;
    }

    /**
     * @throws StorageException
     */
    public function uploadFile(string $file, string $fileExt, string $dir = null): array
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

            return $this->responseDataExtractor->extract($response);
        } catch (Throwable $exception) {
            throw StorageException::uploadProblem($exception->getMessage());
        }
    }

    /**
     * @throws StorageException
     */
    public function downloadFile(string $file): array
    {
        try {
            $parameters = json_encode(['file' => $file]);
            $request = $request = new Request('GET', $this->host . self::DOWNLOAD_URI, [], $parameters);
            $response = $this->client->sendRequest($request);

            return $this->responseDataExtractor->extract($response);
        } catch (Throwable $exception) {
            throw StorageException::downloadProblem($exception->getMessage());
        }
    }
}
