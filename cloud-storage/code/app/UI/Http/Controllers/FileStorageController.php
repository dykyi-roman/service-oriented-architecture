<?php

namespace App\UI\Http\Controllers;

use App\Application\ResponseFactory;
use App\Domain\Client;
use App\UI\Api\ApiController;
use DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class FileStorageController extends ApiController
{
    /**
     * @OA\Post(
     *     tags={"Cloud-storage"},
     *     path="/api/storage/folder/create",
     *     summary="Create folder",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */
    public function createFolder(Request $request, Client $client): JsonResponse
    {
        try {
            $client->init(env('APP_ADAPTERS'), env('APP_RESERVE_ADAPTER'));
            $client->createFolder($request->get('name'));

            return new JsonResponse(ResponseFactory::success());
        } catch (DomainException $exception) {
            return new JsonResponse(ResponseFactory::error($exception->getMessage()), 500);
        }
    }
}
