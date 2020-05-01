<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class GuzzleClient implements ClientInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->send($request, $this->defaultOptions($request->getHeaders()));
    }

    private function defaultOptions(array $headers): array
    {
        return [
            'verify' => false,
            'http_errors' => false,
            'headers' => array_merge(['Content-Type' => 'application/json'], $headers),
        ];
    }
}
