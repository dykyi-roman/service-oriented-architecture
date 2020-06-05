<?php

declare(strict_types=1);

namespace App\UI\Http\Rest;

use App\Application\Common\Error;
use App\Application\User\Command\UserRegisterCommand;
use App\Domain\User\Entity\User;
use App\Domain\User\Request\UserRegistrationRequest;
use App\Domain\User\Service\PasswordRestore;
use App\Domain\User\ValueObject\FullName;
use Exception;
use Immutable\Exception\ImmutableObjectException;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Throwable;

/**
 * @OA\Tag(name="User")
 */
class UserController extends ApiController
{
    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/user/login",
     *     summary="Get current user from token storage",
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
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/user",
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
     * @Route(path="/api/user", methods={"POST"}, name="api.user.registration")
     */
    public function register(Request $request, CommandBus $commandBus): JsonResponse
    {
        try {
            $uuid = Uuid::uuid4();
            $request = $this->transformJsonBody($request);
            $commandBus->handle(
                new UserRegisterCommand(
                    $uuid,
                    new UserRegistrationRequest(
                        $request->get('email'),
                        $request->get('password'),
                        $request->get('phone'),
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
     * @OA\Get(
     *     tags={"User"},
     *     security= { { "bearerAuth": {} } },
     *     path="/api/user",
     *     summary="Get current user from token storage",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route(path="/api/user", methods={"GET"}, name="api.user.current_info")
     */
    public function user(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            return $this->respondNotFound(Error::create('Token not Found!'));
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return $this->respondNotFound(Error::create('User not Found!'));
        }

        return $this->respondWithSuccess($user->toArray());
    }

    /**
     * @OA\Put(
     *     tags={"User"},
     *     security= { { "bearerAuth": {} } },
     *     path="/api/user/password/restore",
     *     summary="Restore password",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route(path="/api/user/password/restore", methods={"PUT"}, name="api.user.password.restore")
     */
    public function passwordRestore(Request $request, PasswordRestore $passwordRestore): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);
            $passwordRestore->restore($request->get('contact', ''), $request->get('password', ''));
        } catch (Throwable $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }

        return $this->respondWithSuccess();
    }
}
