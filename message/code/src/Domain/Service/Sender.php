<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Event\NotSentEvent;
use App\Domain\Event\SentEvent;
use App\Domain\ValueObject\Message;
use App\Domain\ValueObject\MessageType;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\InvalidArgumentException;

final class Sender
{
    private MessageSenderFactory $senderFactory;
    private EventDispatcherInterface $dispatcher;
    private TemplateFinder $templateFinder;
    private LoggerInterface $logger;

    public function __construct(
        MessageSenderFactory $senderFactory,
        TemplateFinder $templateFinder,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
        $this->dispatcher = $dispatcher;
        $this->senderFactory = $senderFactory;
        $this->templateFinder = $templateFinder;
    }

    public function execute(array $data): void
    {
        try {
            foreach ($data['to'] as $recipient) {
                $messageType = (new MessageType($recipient));
                $template = $this->templateFinder->find($data['template'], $messageType->toString(), $data['language']);
                $sender = $this->senderFactory->create(new MessageType($recipient));
                $sender->send(new Message($template, new MessageType($recipient), $recipient));

                $this->dispatcher->dispatch(new SentEvent($data['id'], $template));
            }
        } catch (Exception | InvalidArgumentException $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, ['error' => $exception->getMessage()]);

            $this->dispatcher->dispatch(new NotSentEvent($data['id'], $template ?? null, $exception->getMessage()));
        }
    }
}
