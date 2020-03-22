<?php

declare(strict_types=1);

namespace App\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController extends AbstractController
{
    protected int $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function response(array $data, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    public function respondWithErrors(string $errors, int $statusCode = 500, array $headers = []): JsonResponse
    {
        $data = [
            'status' => 'error',
            'errors' => $errors,
        ];

        return new JsonResponse($data, $statusCode, $headers);
    }

    public function respondWithSuccess(array $data = [], int $statusCode = 200, array $headers = []): JsonResponse
    {
        $data = [
            'status' => 'success',
            'data' => $data
        ];

        return new JsonResponse($data, $statusCode, $headers);
    }

    public function respondUnauthorized(string $message = 'Not authorized!'): JsonResponse
    {
        return $this->respondWithErrors($message,  401);
    }

    public function respondValidationError(string $message = 'Validation errors'): JsonResponse
    {
        return $this->respondWithErrors($message,  422);
    }

    public function respondNotFound(string $message = 'Not found!'): JsonResponse
    {
        return $this->respondWithErrors($message,  404);
    }

    public function respondCreated(array $data = []): JsonResponse
    {
        return $this->respondWithSuccess($data,  201);
    }

    // this method allows us to accept JSON payloads in POST requests
    // since Symfony 4 doesn’t handle that automatically:
    protected function transformJsonBody(Request $request): Request
    {
        if ('' === $request->getContent()) {
            return $request;
        }

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
