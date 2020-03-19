<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\UserRegisterCommand;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Exception\UserException;
use App\Domain\Repository\UserRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

final class UserRegisterHandler
{
    private LoggerInterface $logger;
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritDoc
     *
     * @throws UserException
     */
    public function handle(UserRegisterCommand $command): void
    {
        try {
            $this->userRepository->createUser(
                $command->getUuid(),
                $command->getEmail()->getAddress(),
                $command->getPassword(),
                $command->getPhone()->toString(),
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
