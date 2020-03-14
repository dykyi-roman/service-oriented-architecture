<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Domain\Service\UserFinder;
use App\Domain\Transformer\Api\UserApiTransformer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends ApiController
{
    public function user(Request $request, UserFinder $userFinder): JsonResponse
    {
        $id = $request->get('id', '');
        if (false === Uuid::isValid($id)) {
            return $this->respondNotFound();
        }

        $user = $userFinder->findById($id);
        if (null === $user) {
            return $this->respondNotFound();
        }

        return $this->respondWithSuccess(UserApiTransformer::transform($user));
    }
}
