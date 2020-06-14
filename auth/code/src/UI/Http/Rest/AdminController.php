<?php

declare(strict_types=1);

namespace App\UI\Http\Rest;

use App\Application\Common\Error;
use App\Application\User\Command\AdminRegisterCommand;
use App\Application\User\Command\UserUpdateCommand;
use App\Application\User\DTO\ApiUserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Request\AdminRegistrationRequest;
use App\Domain\User\Request\UserUpdateRequest;
use App\Domain\User\Service\UserFinder;
use App\Domain\User\ValueObject\FullName;
use Exception;
use Immutable\Exception\ImmutableObjectException;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
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
     *     path="/api/admin/users/{id}",
     *     summary="Get user by id",
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

    /**
     * @Route("/api/admin/users/{id}", methods={"GET"}, name="api.admin.user")
     */
    public function getUserById(Request $request, UserFinder $userFinder): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);
            $user = $userFinder->findUserById($request->get('id', ''));
            if (null === $user) {
                throw new UserNotFoundException('id', $request->get('id', ''));
            }

            return $this->respondWithSuccess(ApiUserDTO::transform($user));
        } catch (Throwable $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/admin/users",
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/admin/users", methods={"GET"}, name="api.admin.user.all")
     */
    public function getUsers(UserFinder $userFinder): JsonResponse
    {
        try {
            $users = $userFinder->findAll();

            return $this->respondWithSuccess(array_map(fn(User $user) => ApiUserDTO::transform($user), $users));
        } catch (Throwable $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Post(
     *     tags={"Admin"},
     *     path="/api/admin/user",
     *     summary="Register a new user",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="firstName",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="lastName",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route(path="/api/admin/users", methods={"POST"}, name="api.admin.user.registration")
     */
    public function register(Request $request, CommandBus $commandBus): JsonResponse
    {
        try {
            $uuid = Uuid::uuid4();
            $request = $this->transformJsonBody($request);
            $commandBus->handle(
                new AdminRegisterCommand(
                    $uuid,
                    new AdminRegistrationRequest(
                        $request->get('email'),
                        $request->get('password'),
                        new FullName(
                            $request->get('firstName'),
                            $request->get('lastName')
                        )
                    )
                )
            );
        } catch (Exception | InvalidArgumentException | ImmutableObjectException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }

        return $this->respondCreated(['uuid' => $uuid->toString()]);
    }

    /**
     * @OA\Put(
     *     tags={"Admin"},
     *     path="/api/admin/users/{id}",
     *     summary="Update user",
     *     @OA\Parameter(
     *          name="id",
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
     *                  property="fullName",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="active",
     *                  type="boolean",
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route(path="/api/admin/users/{id}", methods={"PUT"}, name="api.admin.user.update")
     */
    public function update(Request $request, CommandBus $commandBus): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);
            $commandBus->handle(
                new UserUpdateCommand(
                    Uuid::fromString($request->get('id', '')),
                    new UserUpdateRequest(
                        $request->get('fullName', ''),
                        $request->get('active', '')
                    )
                )
            );
        } catch (Exception | InvalidArgumentException | ImmutableObjectException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }

        return $this->respondUpdated([]);
    }
}
