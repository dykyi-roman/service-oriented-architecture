<?php

declare(strict_types=1);

namespace App\UI\Api;

use App\Application\Command\UserRegisterCommand;
use App\Domain\Entity\User;
use App\Domain\Transformer\Api\UserApiTransformer;
use App\Domain\VO\FullName;
use App\Domain\VO\UserRegistrationRequest;
use Immutable\Exception\ImmutableObjectException;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @OA\Info(title="Auth API", version="1.0.3")
 * @OA\Tag(name="User")
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 * )
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
     *     path="/api/token/refresh",
     *     security= { { "bearerAuth": {} } },
     *     summary="Refresh user token",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="refresh_token",
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
     *     path="/api/user/registration",
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
     *                  description="phone|email"
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
     * @Route(path="/api/user/registration", methods={"POST"}, name="user_registration")
     * @inheritDoc
     */
    public function register(Request $request, CommandBus $commandBus): JsonResponse
    {
        try {
            $uuid = Uuid::uuid4();
            $request = $this->transformJsonBody($request);
            $commandBus->handle(new UserRegisterCommand(
                $uuid,
                new UserRegistrationRequest(
                    $request->get('email'),
                    $request->get('password'),
                    $request->get('phone'),
                    new FullName(
                        $request->get('firstName'),
                        $request->get('lastName'),
                    )
                )
            ));
        } catch (\Exception | \InvalidArgumentException | ImmutableObjectException $exception) {
            return $this->respondWithErrors($exception->getMessage());
        }

        return $this->respondCreated(['uuid' => $uuid->toString()]);
    }

    /**
     * @OA\Get(
     *     tags={"User"},
     *     security= { { "bearerAuth": {} } },
     *     path="/api/user/current",
     *     summary="Get current user from token storage",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route(path="/api/user/current", methods={"GET"}, name="user_current_info")
     * @inheritDoc
     */
    public function user(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            return $this->respondNotFound();
        }

        /** @var User $user */
        $user = $token->getUser();

        if (null === $user) {
            return $this->respondNotFound();
        }

        return $this->respondWithSuccess(UserApiTransformer::transform($user));
    }
}
