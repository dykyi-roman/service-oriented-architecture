<?php

namespace App\UI\Http\Controllers;

use App\Domain\Service\Client;
use DomainException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class FileStorageController extends ApiController
{
    private Client $client;

    /**
     * @inheritDoc
     * @throws \App\Domain\Exception\AdapterException
     */
    public function __construct(Client $client)
    {
        $client->init(env('APP_ADAPTERS'));
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
            $result = $this->client->createFolder($request->get('name', ''));
            return $this->respondWithSuccess($result);
        } catch (DomainException $exception) {
            return $this->respondWithErrors($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     tags={"Cloud-storage"},
     *     path="/api/storage/upload",
     *     summary="Upload file",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="file",
     *                      type="string",
     *                      format="binary"
     *                  ),
     *                  @OA\Property(
     *                      property="dir",
     *                      type="string",
     *                      description="file path to storage"
     *                  ),
     *              )
     *          )
     *      ),
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
            if (null === $file) {
                return $this->respondWithErrors('File is empty');
            }

            $result = $this->client->upload(
                (string)$file->getRealPath(),
                $request->get('dir', ''),
                $file->getClientOriginalExtension(),
                );

            return $this->respondWithSuccess($result);
        } catch (DomainException $exception) {
            return $this->respondWithErrors($exception->getMessage());
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

            return $this->respondWithSuccess($data);
        } catch (DomainException $exception) {
            return $this->respondWithErrors($exception->getMessage());
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

            return $this->respondWithSuccess();
        } catch (DomainException $exception) {
            return $this->respondWithErrors($exception->getMessage());
        }
    }
}