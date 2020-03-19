<?php

namespace App\Domain\Service;

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

    public function create(MessageType $messageType): MessageSenderInterface
    {
        if ($messageType->isPhone()) {
            return $this->container->get(TwilioClient::class);
        }

        return $this->container->get(PHPMailerClient::class);
    }
}
