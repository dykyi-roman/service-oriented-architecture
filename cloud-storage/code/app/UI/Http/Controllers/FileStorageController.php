<?php

namespace App\UI\Http\Controllers;

use App\Application\ResponseFactory;
use App\Domain\Client;
use DomainException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class FileStorageController extends ApiController
{
    private Client $client;

    public function __construct(Client $client)
    {
        $client->init(env('APP_ADAPTERS'), env('APP_RESERVE_ADAPTER'));
        $this->client = $client;
    }

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
    public function createFolder(Request $request): JsonResponse
    {
        try {
            $this->client->createFolder($request->get('name', ''));
            return new JsonResponse(ResponseFactory::success());
        } catch (DomainException $exception) {
            return new JsonResponse(ResponseFactory::error($exception->getMessage()), 500);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Cloud-storage"},
     *     path="/api/storage/upload",
     *     summary="Upload file",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="dir",
     *                  type="string",
     *                  description="file path to storage"
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */
    public function uploadFile(Request $request): JsonResponse
    {
        try {
            /** @var UploadedFile $file */
            $file = $request->files->get('file');
            $uploadFilePath = $request->get('dir', '') . '/' . $file->getClientOriginalName();
            $this->client->upload((string)$file->getRealPath(), '/' . ltrim($uploadFilePath, '/'));

            return new JsonResponse(ResponseFactory::success());
        } catch (DomainException $exception) {
            return new JsonResponse(ResponseFactory::error($exception->getMessage()), 500);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Cloud-storage"},
     *     path="/api/storage/download",
     *     summary="Download file",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="file",
     *                  type="string",
     *                  description="file path to storage"
     *              ),
     *              @OA\Property(
     *                  property="dir",
     *                  type="string",
     *                  description="dir to download"
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */
    public function download(Request $request): JsonResponse
    {
        try {
            $data = $this->client->download($request->get('file'), $request->get('dir'));

            return new JsonResponse(ResponseFactory::success($data));
        } catch (DomainException $exception) {
            return new JsonResponse(ResponseFactory::error($exception->getMessage()), 500);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Cloud-storage"},
     *     path="/api/storage/delete",
     *     summary="Delete file or folder",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="path",
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
    public function delete(Request $request): JsonResponse
    {
        try {
            $this->client->delete($request->get('path'));

            return new JsonResponse(ResponseFactory::success());
        } catch (DomainException $exception) {
            return new JsonResponse(ResponseFactory::error($exception->getMessage()), 500);
        }
    }
}
