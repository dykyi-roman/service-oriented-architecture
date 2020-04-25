<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class JWTAuth
{
    private const LOGIN_URI = '/api/user/login';

    private ClientInterface $client;
    private LoggerInterface $logger;
    private ResponseDataExtractorInterface $responseDataExtractor;

    public function __construct(
        ClientInterface $client,
        LoggerInterface $logger,
        ResponseDataExtractorInterface $responseDataExtractor
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->responseDataExtractor = $responseDataExtractor;
    }

    public function authorizeByEmail(string $email, string $password): array
    {
        try {
            $payload = json_encode(['email' => $email, 'password' => $password], JSON_THROW_ON_ERROR, 512);
            $request = new Request('POST', self::LOGIN_URI, [], $payload);

            $response = $this->client->sendRequest($request);

            return $this->responseDataExtractor->extract($response);
        } catch (Throwable $exception) {
            $this->logger->error('Application::Security', ['error' => $exception->getMessage()]);
            throw AuthException::unexpectedErrorInAuthoriseProcess();
        }
    }

    public function authenticate(): bool
    {
        return true;
    }
}
