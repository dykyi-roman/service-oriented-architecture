<?php

declare(strict_types=1);

namespace App\Application\User\Handler;

use App\Application\Common\Service\ExceptionLogger;
use App\Application\User\Command\AdminRegisterCommand;
use App\Domain\User\Event\UserRegisteredEvent;
use App\Domain\User\Exception\UserException;
use App\Domain\User\Repository\WriteUserRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class AdminRegisterHandler
{
    private LoggerInterface $logger;
    private WriteUserRepositoryInterface $userRepository;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        WriteUserRepositoryInterface $userRepository,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritDoc
     *
     * @throws UserException
     */
    public function handle(AdminRegisterCommand $command): void
    {
        try {
            $this->userRepository->createAdmin(
                $command->uuid,
                $command->email->getAddress(),
                $command->password,
                $command->fullName->toString()
            );
            $this->dispatcher->dispatch(new UserRegisteredEvent($command->uuid, $command->email));
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));

            throw UserException::createUser();
        }
    }
}
