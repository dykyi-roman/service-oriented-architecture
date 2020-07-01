<?php

declare(strict_types=1);

namespace App\Application\User\Handler;

use App\Application\Common\Service\ExceptionLogger;
use App\Application\User\Command\UserRegisterCommand;
use App\Domain\User\Event\UserRegisteredEvent;
use App\Domain\User\Exception\UserException;
use App\Domain\User\Repository\WriteUserRepositoryInterface;
use App\Infrastructure\Metrics\MetricsInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class UserRegisterHandler
{
    private LoggerInterface $logger;
    private WriteUserRepositoryInterface $userRepository;
    private EventDispatcherInterface $dispatcher;
    private MetricsInterface $metrics;

    public function __construct(
        WriteUserRepositoryInterface $userRepository,
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
                $command->uuid,
                $command->email->getAddress(),
                $command->password,
                $command->phone->toString(),
                $command->fullName->toString()
            );
            $this->metrics->endTiming('registration', 1.0, ['error' => 0]);
            $this->metrics->inc('registration');
            $this->dispatcher->dispatch(new UserRegisteredEvent($command->uuid, $command->email));
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            $this->metrics->endTiming('registration', 1.0, ['error' => 1]);

            throw UserException::createUser();
        }
    }
}
