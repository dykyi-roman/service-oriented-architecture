<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Application\Command\UserRegisterCommand;
use App\Domain\VO\UserRegistrationRequest;
use Immutable\Exception\ImmutableObjectException;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends ApiController
{
    public function register(Request $request, CommandBus $commandBus): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);
            $commandBus->handle(new UserRegisterCommand(
                new UserRegistrationRequest(
                    $request->get('email'),
                    $request->get('password'),
                    $request->get('username')
                )
            ));
        } catch (ImmutableObjectException $exception) {
            return $this->respondWithErrors($exception->getMessage(), [], 500);
        } catch (\InvalidArgumentException $exception) {
            return $this->respondValidationError($exception->getMessage());
        } catch (\Throwable $exception) {
            return $this->respondWithErrors($exception->getMessage(), [], 500);
        }

        return $this->respondWithSuccess(sprintf('User successfully created'));
    }
}
