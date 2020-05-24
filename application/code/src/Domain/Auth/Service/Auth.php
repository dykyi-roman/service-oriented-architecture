<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Password;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

final class Auth
{
    private const LOGIN_URI = '/api/user/login';
    private const RESTORE_PASSWORD_URI = '/api/user/password/restore';

    private string $host;
    private ClientInterface $client;
    private ResponseDataExtractorInterface $responseDataExtractor;

    public function __construct(
        ClientInterface $client,
        ParameterBagInterface $bag,
        ResponseDataExtractorInterface $responseDataExtractor
    ) {
        $this->host = $bag->get('AUTH_SERVICE_HOST');
        $this->client = $client;
        $this->responseDataExtractor = $responseDataExtractor;
    }

    public function authorizeByEmail(Email $email, Password $password): array
    {
        try {
            $payload = json_encode(
                [
                    'email' => $email->toString(),
                    'password' => $password->toString()
                ],
                JSON_THROW_ON_ERROR | JSON_THROW_ON_ERROR,
                512
            );
            $request = new Request('POST', $this->host . self::LOGIN_URI, [], $payload);
            $response = $this->client->sendRequest($request);

            return $this->responseDataExtractor->extract($response);
        } catch (Throwable $exception) {
            throw AuthException::unexpectedErrorInAuthoriseProcess($exception->getMessage());
        }
    }

    public function passwordRestore(string $contact, Password $password): array
    {
        try {
            $payload = json_encode(
                [
                    'contact' => $contact,
                    'password' => $password->toString()
                ],
                JSON_THROW_ON_ERROR | JSON_THROW_ON_ERROR,
                512
            );
            $request = new Request('PUT', $this->host . self::RESTORE_PASSWORD_URI, [], $payload);
            $response = $this->client->sendRequest($request);

            return $this->responseDataExtractor->extract($response);
        } catch (Throwable $exception) {
            throw AuthException::unexpectedErrorInAuthoriseProcess($exception->getMessage());
        }
    }
}
