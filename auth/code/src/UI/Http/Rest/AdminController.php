<?php

declare(strict_types=1);

namespace App\UI\Http\Rest;

use App\Application\Common\Error;
use App\Domain\User\Service\UserFinder;
use App\Domain\User\Transformer\UsersToArrayTransformer;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @OA\Tag(name="Admin")
 */
class AdminController extends ApiController
{
    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/admin/user/{id}",
     *     summary="Get user by id",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/admin/user/{id}", methods={"GET"}, name="api.admin.user.one")
     */
    public function getOne(Request $request, UserFinder $userFinder): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);
            $user = $userFinder->findById($request->get('id', ''));
            if (null === $user) {
                throw new UserNotFoundException('id', $request->get('id', ''));
            }

            return $this->respondWithSuccess([$user->toArray()]);
        } catch (Throwable $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/admin/user",
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/admin/user", methods={"GET"}, name="api.admin.user.all")
     */
    public function getAll(UserFinder $userFinder): JsonResponse
    {
        try {
            $users = $userFinder->findAll();

            return $this->respondWithSuccess(UsersToArrayTransformer::transform($users));
        } catch (Throwable $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }
}
