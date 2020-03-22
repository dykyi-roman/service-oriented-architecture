<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Event\NotSentEvent;
use App\Domain\Event\SentEvent;
use App\Domain\Exception\MessageException;
use App\Domain\Exception\TemplateException;
use App\Domain\Service\MessageSenderFactory;
use App\Domain\Service\TemplateLoader;
use App\Domain\ValueObject\Message;
use App\Domain\ValueObject\MessageType;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\InvalidArgumentException;
use stdClass;

final class Sender
{
    private MessageSenderFactory $senderFactory;
    private EventDispatcherInterface $dispatcher;
    private TemplateLoader $templateLoader;
    private LoggerInterface $logger;

    public function __construct(
        MessageSenderFactory $senderFactory,
        TemplateLoader $templateLoader,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
        $this->dispatcher = $dispatcher;
        $this->senderFactory = $senderFactory;
        $this->templateLoader = $templateLoader;
    }

    public function execute(stdClass $data): void
    {
        try {
            foreach ($data->to as $type => $recipient) {
                $messageType = new MessageType($type);
                $template = $this->templateLoader->load($data->template, $messageType);

                $message = new Message($template, $messageType, $recipient);
                $sender = $this->senderFactory->create($messageType);
                $sender->send($message);

                $this->dispatcher->dispatch(new SentEvent($data->user_id, $template));
            }
        } catch (MessageException | TemplateException | ImmutableObjectException | InvalidValueException | InvalidArgumentException $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, ['error' => $exception->getMessage()]);

            $event = new NotSentEvent($data->user_id, $template ?? null, $exception->getMessage());
            $this->dispatcher->dispatch($event);
        }
    }
}
