<?php

declare(strict_types=1);

namespace App\Application\Sender\MessageHandler;

use App\Application\JsonSchemaValidator;
use App\Application\Sender\Message\Message;
use App\Application\Sender\Service\ProviderSender;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MessageHandler implements MessageHandlerInterface
{
    private const SCHEMA = 'queue.json';

    private LoggerInterface $logger;
    private JsonSchemaValidator $schemaValidator;
    private ProviderSender $providerSender;

    public function __construct(
        JsonSchemaValidator $schemaValidator,
        ProviderSender $providerSender,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->schemaValidator = $schemaValidator;
        $this->providerSender = $providerSender;
    }

    public function __invoke(Message $message)
    {
        try {
            $content = $message->getContent();
            $this->schemaValidator->validate($content, self::SCHEMA);
            $this->providerSender->send($content);
        } catch (JsonException $exception) {
            $this->logger->error('Application::MessageHandler', ['error' => $exception->getMessage()]);
        }
    }
}
