<?php

declare(strict_types=1);

namespace App\Application\Consumer;

use App\Application\Service\Sender;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class MessageConsumer
{
    private Sender $sender;
    private ParameterBagInterface $bag;

    public function __construct(ParameterBagInterface $bag, Sender $sender)
    {
        $this->bag = $bag;
        $this->sender = $sender;
    }

    public function execute(string $queueName): void
    {
        $exchange = 'fanout_message_exchange';
        $queue = $queueName;
        $consumerTag = 'consumer' . getmypid();

        $connection = new AMQPStreamConnection(
            $this->bag->get('RABBITMQ_HOST'),
            $this->bag->get('RABBITMQ_PORT'),
            $this->bag->get('RABBITMQ_USER'),
            $this->bag->get('RABBITMQ_PASSWORD'),
            $this->bag->get('RABBITMQ_VHOST'),
        );
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, false, false, true);
        $channel->exchange_declare($exchange, AMQPExchangeType::FANOUT, false, false, true);
        $channel->queue_bind($queue, $exchange);
        $channel->basic_consume($queue, $consumerTag, false, false, false, false, [$this, 'process_message']);

        register_shutdown_function([$this, 'shutdown'], $channel, $connection);
        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    public function process_message($message): void
    {
        $body = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR);
        $this->sender->execute($body);

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }
    }

    public function shutdown($channel, $connection): void
    {
        $channel->close();
        $connection->close();
    }
}
