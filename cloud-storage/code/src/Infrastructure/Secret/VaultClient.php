<?php

declare(strict_types=1);

namespace App\Infrastructure\Secret;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use function json_decode;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @see https://www.vaultproject.io/api-docs
 */
final class VaultClient implements SecretReadInterface
{
    private array $options;
    private ClientInterface $client;
    private bool $init;

    public function __construct(ClientInterface $client, string $host, string $token)
    {
        $this->client = $client;
        $this->options = $this->setOptions($host, $token);
        $this->init = $this->init();
    }

    private function init(): bool
    {
        try {
            $response = $this->client->send(new Request('GET', '/v1/sys/init'), $this->options);
            $data = $this->extract($response);

            return $data['initialized'] === true;
        } catch (Throwable $exception) {
            return false;
        }
    }

    public function read(string $key, string $engine = self::KV_ENGINE): array
    {
        if (!$this->init) {
            return [];
        }

        try {
            $request = new Request('GET', sprintf('/v1/%s/data/%s', $engine, $key));
            $response = $this->client->send($request, $this->options);
            $data = $this->extract($response);
            if (is_array($data) && isset($data['data']['data'])) {
                return $data['data']['data'];
            }

            return [];
        } catch (Throwable $exception) {
            return [];
        }
    }

    private function setOptions(string $host, string $token): array
    {
        return [
            'base_uri' => $host,
            'http_errors' => false,
            'headers' => [
                'X-Vault-Token' => $token,
                'Content-Type' => 'application/json',
            ],
        ];
    }

    private function extract(ResponseInterface $response): array
    {
        try {
            $responseBody = (string)$response->getBody()->getContents();

            return (array)json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return [];
        }
    }
}
