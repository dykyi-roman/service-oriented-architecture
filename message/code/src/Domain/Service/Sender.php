<?php

namespace App\Domain\Service;

use App\Domain\Event\NotSentEvent;
use App\Domain\Event\SentEvent;
use App\Domain\ValueObject\Message;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\Template;
use Immutable\Exception\ImmutableObjectException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

final class Sender
{
    private MessageSenderFactory $senderFactory;
    private EventDispatcherInterface $dispatcher;
    private TemplateFinder $templateFinder;
    /**
     * @var LoggerInterface
     */
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
                $template = $this->templateFinder->find(
                    $data['template'],
                    (new MessageType($recipient))->toString(),
                    $data['language']
                );

                $this->sendTo($recipient, $template);
                $this->dispatcher->dispatch(new SentEvent($data['id'], $template));
            }
        } catch (Throwable $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, ['error' => $exception->getMessage()]);

            $this->dispatcher->dispatch(new NotSentEvent($data['id'], $template, $exception->getMessage()));
        }
    }

    /**
     * @inheritDoc
     *
     * @throws ImmutableObjectException
     * @throws \App\Domain\Exception\MessageException
     * @throws \Immutable\Exception\InvalidValueException
     */
    private function sendTo(string $recipient, Template $template): void
    {
        $messageType = new MessageType($recipient);
        $message = new Message($template, $messageType, $recipient);
        $sender = $this->senderFactory->create($messageType);
        $sender->send($message);
    }
}
