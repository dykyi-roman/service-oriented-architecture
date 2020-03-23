<?php

declare(strict_types=1);

namespace App\Application\Consumer;

use App\Application\Service\JsonSchemaValidator;
use App\Application\Service\Sender;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class MessageConsumer
{
    private const SCHEMA = 'queue.json';
    private const MESSAGE_EXCHANGE = 'router';
    private const CONSUMER_TAG = 'consumer';

    private Sender $sender;
    private ParameterBagInterface $bag;
    private JsonSchemaValidator $schemaValidator;
    private LoggerInterface $logger;

    public function __construct(
        Sender $sender,
        ParameterBagInterface $bag,
        JsonSchemaValidator $schemaValidator,
        LoggerInterface $logger = null
    ) {
        $this->bag = $bag;
        $this->sender = $sender;
        $this->schemaValidator = $schemaValidator;
        $this->logger = $logger ?? new NullLogger();
    }

    public function execute(string $queue): void
    {
        $connection = new AMQPStreamConnection(
            $this->bag->get('RABBITMQ_HOST'),
            $this->bag->get('RABBITMQ_PORT'),
            $this->bag->get('RABBITMQ_USER'),
            $this->bag->get('RABBITMQ_PASSWORD'),
            $this->bag->get('RABBITMQ_VHOST'),
        );

        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare(self::MESSAGE_EXCHANGE, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind($queue, self::MESSAGE_EXCHANGE);
        $channel->basic_consume($queue, self::CONSUMER_TAG, false, false, false, false, [$this, 'processMessage']);

        register_shutdown_function([$this, 'shutdown'], $channel, $connection);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    public function processMessage(AMQPMessage $message): void
    {
        try {
            $content = json_decode($message->body, false, 512, JSON_THROW_ON_ERROR);
            $this->schemaValidator->validate($content, self::SCHEMA);
            $this->sender->execute($content);
        } catch (\Throwable $exception) {
            $this->logger->error('Message::MessageConsumer', ['error' => $exception->getMessage()]);
        }

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        if ($message->body === 'quit') {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }
    }

    public function shutdown(AMQPChannel $channel, AbstractConnection $connection): void
    {
        $channel->close();
        $connection->close();
    }
}
