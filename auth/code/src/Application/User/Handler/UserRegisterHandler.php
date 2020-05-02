<?php

declare(strict_types=1);

namespace App\Application\User\Handler;

use App\Application\User\Command\UserRegisterCommand;
use App\Domain\User\Event\UserRegisteredEvent;
use App\Domain\User\Exception\UserException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Metrics\MetricsInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class UserRegisterHandler
{
    private LoggerInterface $logger;
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $dispatcher;
    private MetricsInterface $metrics;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $dispatcher,
        MetricsInterface $metrics,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
        $this->metrics = $metrics;
    }

    /**
     * @inheritDoc
     *
     * @throws UserException
     */
    public function handle(UserRegisterCommand $command): void
    {
        try {
            $this->metrics->startTiming('registration');
            $this->userRepository->createUser(
                $command->getUuid(),
                $command->getEmail()->getAddress(),
                $command->getPassword(),
                $command->getPhone()->toString(),
                $command->getFullName()
            );
            $this->metrics->endTiming('registration', 1.0, ['error' => 0]);
            $this->metrics->inc('registration');
            $this->dispatcher->dispatch(new UserRegisteredEvent($command->getUuid(), $command->getEmail()));
        } catch (Throwable $exception) {
            $message = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($message, ['error' => $exception->getMessage()]);
            $this->metrics->endTiming('registration', 1.0, ['error' => 1]);

            throw UserException::createUser();
        }
    }
}
