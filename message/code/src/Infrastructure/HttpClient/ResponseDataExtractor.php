<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient;

use Psr\Http\Message\ResponseInterface;

final class ResponseDataExtractor implements ResponseDataExtractorInterface
{
    /**
     * @inheritDoc
     */
    public function extract(ResponseInterface $response): array
    {
        $responseBody = (string)$response->getBody()->getContents();

        return (array)json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
    }
}
