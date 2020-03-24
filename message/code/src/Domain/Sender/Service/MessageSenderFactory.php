<?php

namespace App\Domain\Sender\Service;

use App\Domain\Sender\Exception\MessageException;
use App\Domain\Sender\MessageSenderInterface;
use App\Domain\Sender\ValueObject\MessageType;
use App\Infrastructure\Sender\PHPMailerClient;
use App\Infrastructure\Sender\TwilioClient;
use Psr\Container\ContainerInterface;

final class MessageSenderFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     * @return MessageSenderInterface
     * @throws MessageException
     */
    public function create(MessageType $type): MessageSenderInterface
    {
        if ($type->isPhone()) {
            return $this->container->get(TwilioClient::class);
        }

        if ($type->isEmail()) {
            return $this->container->get(PHPMailerClient::class);
        }

        throw MessageException::messageSenderIsNotFound();
    }
}
