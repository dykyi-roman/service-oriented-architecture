<?php

declare(strict_types=1);

namespace App\Application\Sender\MessageHandler;

use App\Application\Common\Service\ExceptionLogger;
use App\Application\Sender\Message\MessageSent;
use App\Domain\Sender\Document\Sent;
use App\Domain\Sender\Repository\SentPersistRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

class MessageSentHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private SentPersistRepositoryInterface $persistRepository;

    public function __construct(SentPersistRepositoryInterface $persistRepository, LoggerInterface $logger)
    {
        $this->persistRepository = $persistRepository;
        $this->logger = $logger;
    }

    public function __invoke(MessageSent $message)
    {
        try {
            $sent = new Sent($message->getUserId());
            $sent->setTemplate($message->getTemplate());

            $this->persistRepository->save($sent);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            echo sprintf('Message::MessageSentHandler::%s', $exception->getMessage());
        }
    }
}
