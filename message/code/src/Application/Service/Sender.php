<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Event\NotSentEvent;
use App\Domain\Event\SentEvent;
use App\Domain\Service\MessageSenderFactory;
use App\Domain\Service\TemplateFinder;
use App\Domain\ValueObject\Message;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\Template;
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
                $template = $this->templateFinder->find(
                    $data['template']['name'],
                    (new MessageType($recipient))->toString(),
                    $data['template']['lang'] ?? Template::DEFAULT_LANGUAGE
                );
                $template = $template->withVariables($template, $data['template']['variables']);
                $sender = $this->senderFactory->create(new MessageType($recipient));
                $sender->send(new Message($template, $recipient));

                $this->dispatcher->dispatch(new SentEvent($data['user_id'], $template));
            }
        } catch (Exception | InvalidArgumentException $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, ['error' => $exception->getMessage()]);

            $event = new NotSentEvent($data['user_id'], $template ?? null, $exception->getMessage());
            $this->dispatcher->dispatch($event);
        }
    }
}
