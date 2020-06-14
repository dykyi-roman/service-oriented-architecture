<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Response\ApiResponse;
use App\Domain\Auth\Response\ApiResponseInterface;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Auth\ValueObject\FullName;
use App\Domain\Auth\ValueObject\Password;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

final class SignUp
{
    private const LOGIN_URI = '/api/admin/users';
    private const GET_USERS_URI = '/api/admin/users';
    private const GET_USER_URI = '/api/admin/users/%s';
    private const UPDATE_USER_URI = '/api/admin/users/%s';

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

    public function createNewUser(Email $email, Password $password, FullName $fullName): ApiResponseInterface
    {
        try {
            $payload = json_encode(
                [
                    'email' => $email->toString(),
                    'password' => $password->toString(),
                    'firstName' => $fullName->firstName(),
                    'lastName' => $fullName->lastName()
                ],
                JSON_THROW_ON_ERROR,
            );
            $request = new Request('POST', $this->host . self::LOGIN_URI, [], $payload);
            $response = $this->client->sendRequest($request);
            $dtaExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dtaExtract);
        } catch (Throwable $exception) {
            throw AuthException::unexpectedErrorInSignUpProcess($exception->getMessage());
        }
    }

    public function getAllUsers(): ApiResponseInterface
    {
        try {
            $request = new Request('GET', $this->host . self::GET_USERS_URI);
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw AuthException::getAllUserError($exception->getMessage());
        }
    }

    public function getUserById(string $id): ApiResponseInterface
    {
        try {
            $request = new Request('GET', $this->host . sprintf(self::GET_USER_URI, $id));
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw AuthException::getUserError($exception->getMessage());
        }
    }

    public function updateUserById(string $id, array $data): ApiResponseInterface
    {
        try {
            $payload = json_encode($data, JSON_THROW_ON_ERROR);
            $request = new Request('PUT', $this->host . sprintf(self::UPDATE_USER_URI, $id), [], $payload);
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw AuthException::getUserError($exception->getMessage());
        }
    }
}
