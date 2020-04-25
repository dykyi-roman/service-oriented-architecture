<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient;

use JsonException;
use Psr\Http\Message\ResponseInterface;

interface ResponseDataExtractorInterface
{
    /**
     * @param ResponseInterface $response
     *
     * @return array
     * @throws JsonException
     */
    public function extract(ResponseInterface $response): array;
}
