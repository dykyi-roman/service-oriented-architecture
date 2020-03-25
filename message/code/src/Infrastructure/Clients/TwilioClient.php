<?php

declare(strict_types=1);

namespace App\Infrastructure\Clients;

use App\Domain\Sender\MessageSenderInterface;
use App\Domain\Sender\MessageInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

/**
 * @see https://github.com/twilio/twilio-php
 */
final class TwilioClient implements MessageSenderInterface
{
    private Client $client;

    private LoggerInterface $logger;

    /**
     * @inheritDoc
     *
     * @throws ConfigurationException
     */
    public function __construct(Client $client, LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger ?? new NullLogger();
    }

    public function send(MessageInterface $message): void
    {
        try {
            $options = ['from' => $message->recipients()->sender()->toString(), 'body' => $message->template()->body()];
            
            $this->client->messages->create($message->recipients()->recipient()->toString(), $options);
        } catch (Throwable $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, [
                'error' => $exception->getMessage(),
                'number' => $message->recipients()->recipient()->toString(),
                'message' => $message->template()->body()
            ]);
        }
    }
}