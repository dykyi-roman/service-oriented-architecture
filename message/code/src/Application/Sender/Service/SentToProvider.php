<?php

declare(strict_types=1);

namespace App\Application\Sender\Service;

use App\Application\Sender\Message\MessageNotSent;
use App\Application\Sender\Message\MessageSent;
use App\Domain\Sender\Exception\MessageException;
use App\Domain\Sender\Service\MessageSenderFactory;
use App\Domain\Sender\ValueObject\Message;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\TemplateLoader;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\InvalidArgumentException;
use stdClass;
use Symfony\Component\Messenger\MessageBusInterface;

final class SentToProvider
{
    private MessageSenderFactory $senderFactory;
    private TemplateLoader $templateLoader;
    private LoggerInterface $logger;
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageSenderFactory $senderFactory,
        TemplateLoader $templateLoader,
        MessageBusInterface $messageBus,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->senderFactory = $senderFactory;
        $this->templateLoader = $templateLoader;
        $this->messageBus = $messageBus;
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

                $this->messageBus->dispatch(new MessageSent($data->user_id, $template->toJson()));
            }
        } catch (MessageException | TemplateException | ImmutableObjectException | InvalidValueException | InvalidArgumentException $exception) {
            $this->logger->error('Message::SentToProvider', ['error' => $exception->getMessage()]);

            $tmp = isset($template) ? $template->toJson() : '';
            $event = new MessageNotSent($data->user_id, $tmp, $exception->getMessage());
            $this->messageBus->dispatch($event);
        }
    }
}
