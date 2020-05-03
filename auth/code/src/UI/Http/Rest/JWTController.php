<?php

declare(strict_types=1);

namespace App\UI\Http\Rest;

use App\Application\Common\Error;
use function file_get_contents;
use OpenApi\Annotations as OA;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="JWT")
 */
class JWTController extends ApiController
{
    /**
     * @OA\Post(
     *     tags={"JWT"},
     *     path="/api/token/refresh",
     *     security= { { "bearerAuth": {} } },
     *     summary="Refresh token",
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
     * @OA\Get(
     *     tags={"JWT"},
     *     path="/api/cert",
     *     summary="Get public cert",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route(path="/api/cert", methods={"GET"}, name="api.jwt.cert_public_key")
     */
    public function cert(ParameterBagInterface $bag): JsonResponse
    {
        if (!file_exists($bag->get('JWT_PUBLIC_KEY'))) {
            return $this->respondNotFound(Error::create('Not Found!'));
        }

        return $this->respondWithSuccess([
            'typ' => 'JWT',
            'alg' => 'RS256',
            'key' => file_get_contents($bag->get('JWT_PUBLIC_KEY'))
        ]);
    }
}
