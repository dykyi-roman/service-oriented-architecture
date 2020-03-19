<?php

namespace App\Domain\Service;

use App\Domain\Event\SentEvent;
use App\Domain\ValueObject\Message;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\Template;
use Immutable\Exception\ImmutableObjectException;
use Psr\EventDispatcher\EventDispatcherInterface;

final class Sender
{
    private MessageSenderFactory $senderFactory;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    public function __construct(MessageSenderFactory $senderFactory, EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->senderFactory = $senderFactory;
    }

    /**
     * @inheritDoc
     *
     * @throws ImmutableObjectException
     * @throws \App\Domain\Exception\MessageException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function execute(array $data): void
    {
        $messageType = new MessageType($data['type']);
        $template = new Template('1', '2');
        $sender = $this->senderFactory->create($messageType);
        $message = new Message($template, $messageType, $data['to']);
        $sender->send($message);

        $this->dispatcher->dispatch(new SentEvent($data['id'], $message));
    }
}
