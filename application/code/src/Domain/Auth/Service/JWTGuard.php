<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Exception\AuthException;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

final class JWTGuard
{
    private const CERT_URI = '/api/cert';

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

    public function downloadPublicKey(string $path): bool
    {
        try {
            $request = new Request('GET', self::CERT_URI);
            $response = $this->client->sendRequest($request);
            $key = $this->responseDataExtractor->extract($response);
            if (file_put_contents($path, $key['data']['key'])) {
                return true;
            }
        } catch (\Throwable $exception) {
            $this->logger->error('Application::Security', ['error' => $exception->getMessage()]);
        }

        return false;
    }

    public function verify(string $token, string $key): User
    {
        if (!file_exists($key)) {
            $message = sprintf('Public key is not found by path %s', $key);
            $this->logger->error('Application::Security', ['error' => $message]);
            throw AuthException::publicKeyIsNotFound($key);
        }

        $payload = null;
        try {
            $payload = JWT::decode($token, file_get_contents($key), ['RS256']);
        } catch (ExpiredException $exception) {
            //TODO:: Try to refresh key
            $this->logger->error('Application::Security', ['error' => 'Token is expired']);
        }

        if (null === $payload) {
            $this->logger->error('Application::Security', ['error' => 'Could not extract payload from token']);
            throw AuthException::tokenIsNotDecoded();
        }

        return User::createUserByJWTPayload($payload);
    }
}
