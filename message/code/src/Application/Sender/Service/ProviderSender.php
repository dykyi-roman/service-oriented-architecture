<?php

declare(strict_types=1);

namespace App\Application\Sender\Service;

use App\Application\Common\Service\ExceptionLogger;
use App\Application\Sender\Message\MessageNotSent;
use App\Application\Sender\Message\MessageSent;
use App\Domain\Sender\Exception\MessageException;
use App\Domain\Sender\MessageSenderInterface;
use App\Domain\Sender\Service\MessageSenderFactory;
use App\Domain\Sender\ValueObject\Message;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\TemplateLoader;
use App\Infrastructure\Metrics\MetricsInterface;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProviderSender
{
    private MessageSenderFactory $senderFactory;
    private TemplateLoader $templateLoader;
    private LoggerInterface $logger;
    private MessageBusInterface $messageBus;
    private MetricsInterface $metrics;

    public function __construct(
        MessageSenderFactory $senderFactory,
        TemplateLoader $templateLoader,
        MessageBusInterface $messageBus,
        MetricsInterface $metrics,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->messageBus = $messageBus;
        $this->senderFactory = $senderFactory;
        $this->templateLoader = $templateLoader;
        $this->metrics = $metrics;
    }

    public function send(stdClass $data): void
    {
        try {
            foreach ($data->recipients as $type => $recipient) {
                $messageType = new MessageType($type);
                $template = $this->templateLoader->load($data->template, $messageType);

                $message = new Message($template, $messageType, $recipient);
                $sender = $this->senderFactory->create($messageType);

                $sent = $this->sentMetricsWrapper($sender, $message, $messageType->toString());

                if ($sent) {
                    $this->messageBus->dispatch(new MessageSent($data->userId, $template->toJson()));
                }

                throw MessageException::messageNotSent();
            }
        } catch (MessageException | TemplateException | ImmutableObjectException | InvalidValueException $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));

            $tmp = isset($template) ? $template->toJson() : '';
            $event = new MessageNotSent($data->userId, $tmp, $exception->getMessage());
            $this->messageBus->dispatch($event);
        }
    }

    private function sentMetricsWrapper(MessageSenderInterface $sender, Message $message, string $type): bool
    {
        $this->metrics->startTiming('sent');
        $sent = $sender->send($message);
        $this->metrics->endTiming('sent', 1.0, ['type' => $type, 'error' => (int)!$sent]);
        $this->metrics->inc($sent ? 'sent' : 'not_sent');

        return $sent;
    }
}
