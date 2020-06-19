<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiAuthorizeResponse;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\Password;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Throwable;

final class Auth
{
    private const LOGIN_URI = '/api/user/login';

    private string $host;
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

    public function authorizeByEmail(Email $email, Password $password): ApiAuthorizeResponse
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
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiAuthorizeResponse($dataExtract);
        } catch (Throwable $exception) {
            throw AuthException::unexpectedErrorInAuthoriseProcess($exception->getMessage());
        }
    }
}
