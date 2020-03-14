<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\UserRegisterCommand;
use App\Domain\Exception\CreateUserException;
use App\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

final class UserRegisterHandler
{
    private UserRepositoryInterface $userRepository;

    private LoggerInterface $logger;

    public function __construct(UserRepositoryInterface $userRepository, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     *
     * @throws CreateUserException
     */
    public function handle(UserRegisterCommand $command): void
    {
        try {
            $this->userRepository->createUser(
                $command->getEmail(),
                $command->getPassword(),
                $command->getPhone(),
                $command->getFullName()
            );
        } catch (Throwable $exception) {
            $this->logger->error('UserRegisterHandler::handle', [
                'message' => $exception->getMessage()
            ]);

            throw new CreateUserException('Create user problem');
        }
    }
}
