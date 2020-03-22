<?php

declare(strict_types=1);

namespace App\Application\Consumer;

use App\Application\Service\Sender;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class MessageConsumer
{
    private const MESSAGE_EXCHANGE = 'router';
    private const CONSUMER_TAG = 'consumer';

    private Sender $sender;
    private ParameterBagInterface $bag;

    public function __construct(ParameterBagInterface $bag, Sender $sender)
    {
        $this->bag = $bag;
        $this->sender = $sender;
    }

    public function execute(string $queueName): void
    {
        $exchange = self::MESSAGE_EXCHANGE;
        $queue = $queueName;

        $connection = new AMQPStreamConnection(
            $this->bag->get('RABBITMQ_HOST'),
            $this->bag->get('RABBITMQ_PORT'),
            $this->bag->get('RABBITMQ_USER'),
            $this->bag->get('RABBITMQ_PASSWORD'),
            $this->bag->get('RABBITMQ_VHOST'),
        );
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind($queue, $exchange);
        $channel->basic_consume($queue, self::CONSUMER_TAG, false, false, false, false, [$this, 'processMessage']);

        register_shutdown_function([$this, 'shutdown'], $channel, $connection);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    public function processMessage(AMQPMessage $message): void
    {
        $body = json_decode($message->body, false, 512, JSON_THROW_ON_ERROR);
        $this->sender->execute($body);

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
