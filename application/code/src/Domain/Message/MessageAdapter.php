<?php

namespace App\Domain\Message;

use App\Application\Sender\Message\Message;
use App\Domain\Message\Exception\MessageException;
use App\Domain\Message\ValueObject\Recipient;
use App\Domain\Message\ValueObject\RecipientCollection;
use App\Domain\Message\ValueObject\Template;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class MessageAdapter
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws MessageException
     */
    public function sendWelcomeMessage(string $userId): void
    {
        try {
            $recipients = (new RecipientCollection())
                ->add(new Recipient(Recipient::TYPE_EMAIL, 'mr.dukuy@gmail.com'))
                ->add(new Recipient(Recipient::TYPE_PHONE, '+380938982443'))
                ->get();
            $template = new Template(Template::WELCOME, 'en');

            $message = new Message($userId, $recipients, $template->toArray());
            $this->messageBus->dispatch($message);
        } catch (Throwable $exception) {
            throw MessageException::sendProblem($exception->getMessage());
        }
    }
}
