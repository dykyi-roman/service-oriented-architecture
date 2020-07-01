<?php

declare(strict_types=1);

namespace App\Application\Sender\MessageHandler;

use App\Application\Common\Service\ExceptionLogger;
use App\Application\Sender\Message\MessageNotSent;
use App\Domain\Sender\Document\NotSent;
use App\Domain\Sender\Repository\NotSentPersistRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

class MessageNotSentHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private NotSentPersistRepositoryInterface $persistRepository;

    public function __construct(NotSentPersistRepositoryInterface $persistRepository, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->persistRepository = $persistRepository;
    }

    public function __invoke(MessageNotSent $message)
    {
        try {
            $document = new NotSent($message->getUserId());
            $document->setTemplate($message->getTemplate());
            $document->setError($message->getError());

            $this->persistRepository->save($document);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            echo sprintf('Message::MessageNotSentHandler::%s', $exception->getMessage());
        }
    }
}
