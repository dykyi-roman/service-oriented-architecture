<?php

namespace App\UI\Http\Controllers;

use App\Application\ResponseFactory;
use App\Domain\Client;
use DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileStorageController extends Controller
{
    public function test(Client $client): JsonResponse
    {
        try {
            $client->init(env('APP_ADAPTERS'), env('APP_RESERVE_ADAPTER'));
            $client->createFolder('test23');

            return new JsonResponse(ResponseFactory::success());
        } catch (DomainException $exception) {
            return new JsonResponse(ResponseFactory::error($exception->getMessage()), 500);
        }
    }
}
