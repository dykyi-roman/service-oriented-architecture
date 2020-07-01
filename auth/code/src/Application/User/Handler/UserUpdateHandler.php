<?php

declare(strict_types=1);

namespace App\Application\User\Handler;

use App\Application\Common\Service\ExceptionLogger;
use App\Application\User\Command\UserUpdateCommand;
use App\Domain\User\Exception\UserException;
use App\Domain\User\Repository\WriteUserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class UserUpdateHandler
{
    private LoggerInterface $logger;
    private WriteUserRepositoryInterface $writeUserRepository;

    public function __construct(WriteUserRepositoryInterface $writeUserRepository, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->writeUserRepository = $writeUserRepository;
    }

    /**
     * @throws UserException
     */
    public function handle(UserUpdateCommand $command): void
    {
        try {
            $this->writeUserRepository->updateUser($command->id, $command->fullName->toString(), $command->active);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));

            throw UserException::updateUser($command->id->toString());
        }
    }
}
