<?php

declare(strict_types=1);

namespace App\Application\User\Handler;

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
                $command->getUuid(),
                $command->getEmail()->getAddress(),
                $command->getPassword(),
                $command->getFullName()
            );
            $this->dispatcher->dispatch(new UserRegisteredEvent($command->getUuid(), $command->getEmail()));
        } catch (Throwable $exception) {
            $message = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($message, ['error' => $exception->getMessage()]);

            throw UserException::createUser();
        }
    }
}
