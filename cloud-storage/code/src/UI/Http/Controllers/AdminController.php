<?php

declare(strict_types=1);

namespace App\UI\Http\Controllers;

use App\Application\Common\Error;
use App\Application\Service\Client;
use DomainException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Tag(name="Admin")
 */
class AdminController extends ApiController
{
    private Client $client;

    /**
     * @throws \App\Domain\Storage\Exception\AdapterException
     */
    public function __construct(Client $client)
    {
        $client->init(env('APP_ADAPTERS'));
        $this->client = $client;
    }

    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/admin/user/{userId}/storage/file/{id}",
     *     summary="Get file by Id",
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */
    public function file(Request $request): JsonResponse
    {
        try {
            return $this->respondWithSuccess([]);
        } catch (DomainException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/admin/user/{userId}/storage/files",
     *     summary="Get all files",
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */
    public function files(): JsonResponse
    {
        try {
            return $this->respondWithSuccess([]);
        } catch (DomainException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }
}
