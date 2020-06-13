<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiResponse;
use App\Domain\Auth\Response\ApiResponseInterface;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\FullName;
use App\Domain\Auth\ValueObject\Password;
use App\Domain\Auth\ValueObject\Phone;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

final class SignUp
{
    private const LOGIN_URI = '/api/user';

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

    public function createNewUser(
        Email $email,
        Password $password,
        Phone $phone,
        FullName $fullName
    ): ApiResponseInterface {
        try {
            $payload = json_encode(
                [
                    'email' => $email->toString(),
                    'phone' => $phone->toString(),
                    'password' => $password->toString(),
                    'firstName' => $fullName->firstName(),
                    'lastName' => $fullName->lastName()
                ],
                JSON_THROW_ON_ERROR | JSON_THROW_ON_ERROR,
                512
            );
            $request = new Request('POST', $this->host . self::LOGIN_URI, [], $payload);
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw AuthException::unexpectedErrorInSignUpProcess($exception->getMessage());
        }
    }
}
