<?php

namespace App\Domain\Message\Service;

use App\Domain\Message\ValueObject\Recipient;
use App\Domain\Message\ValueObject\RecipientCollection;
use App\Domain\Message\ValueObject\Template;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Sender\Message\Message;

final class MessageAdapter
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function sendWelcomeMessage(string $userId): void
    {
        $recipients = (new RecipientCollection())
            ->add(new Recipient(Recipient::TYPE_EMAIL, 'mr.dukuy@gmail.com'))
            ->add(new Recipient(Recipient::TYPE_PHONE, '+380938982443'))
            ->get();
        $template = new Template(Template::WELCOME, 'en');

        $message = new Message($userId, $recipients, $template->toArray());
        $this->messageBus->dispatch($message);
    }
}
