<?php

declare(strict_types=1);

namespace App\UI\Http\Controllers;

use App\Application\Common\Error;
use App\Application\Service\Client;
use App\Domain\Storage\ValueObject\UploadFile;
use DomainException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Tag(name="Cloud-storage")
 */
class StorageController extends ApiController
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
     * @OA\Post(
     *     tags={"Cloud-storage"},
     *     path="/api/{userId}/storage/folders",
     *     summary="Create new folder",
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
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
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Post(
     *     tags={"Cloud-storage"},
     *     path="/api/{userId}/storage/files",
     *     summary="Upload file",
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
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
                return $this->respondWithError(Error::create('File is empty'));
            }

            $result = $this->client->upload(new UploadFile(
                (string)$file->getRealPath(),
                $file->getClientOriginalExtension(),
                $request->get('dir', '')
            ));

            return $this->respondWithSuccess($result);
        } catch (DomainException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Get(
     *     tags={"Cloud-storage"},
     *     path="/api/{userId}/storage/files",
     *     summary="Download file",
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
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
            $data = $this->client->download($request->get('file', ''));

            return $this->respondWithSuccess($data);
        } catch (DomainException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Cloud-storage"},
     *     path="/api/{userId}/storage/files",
     *     summary="Delete file or folder",
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
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
            $this->client->delete($request->get('path', ''));

            return $this->respondWithSuccess();
        } catch (DomainException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }
}
