<?php

namespace App\Domain\Service;

use App\Domain\Exception\MessageException;
use App\Domain\ValueObject\MessageType;
use App\Infrastructure\Service\PHPMailerClient;
use App\Infrastructure\Service\TwilioClient;
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
