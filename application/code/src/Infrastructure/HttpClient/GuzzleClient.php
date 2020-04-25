<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class GuzzleClient implements ClientInterface
{
    private Client $client;
    private ParameterBagInterface $bag;

    public function __construct(Client $client, ParameterBagInterface $bag)
    {
        $this->client = $client;
        $this->bag = $bag;
    }

    public function sendRequest(RequestInterface $request, array $headers = []): ResponseInterface
    {
        return $this->client->send($request, $this->defaultOptions($headers));
    }

    private function defaultOptions($headers): array
    {
        return [
            'verify' => false,
            'base_uri' => $this->bag->get('AUTH_SERVICE_HOST'),
            'http_errors' => false,
            'headers' => array_merge(['Content-Type' => 'application/json'], $headers),
        ];
    }
}
