<?php

namespace App\Domain\Service;

use App\Domain\Event\SentEvent;
use App\Domain\Repository\TemplateRepositoryInterface;
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
    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $templateRepository;

    public function __construct(
        MessageSenderFactory $senderFactory,
        TemplateRepositoryInterface $templateRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->dispatcher = $dispatcher;
        $this->senderFactory = $senderFactory;
        $this->templateRepository = $templateRepository;
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
        foreach ($data['to'] as $recipient) {
            $messageType = new MessageType($recipient);
            $template = new Template('1', '2');
            $sender = $this->senderFactory->create($messageType);
            $message = new Message($template, $messageType, $recipient);
            $sender->send($message);
            $this->dispatcher->dispatch(new SentEvent($data['id'], $message));
            dump('stop'); die();
        }
    }
}
