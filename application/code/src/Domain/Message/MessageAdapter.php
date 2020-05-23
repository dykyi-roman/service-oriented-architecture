<?php

namespace App\Domain\Message;

use App\Application\Sender\Message\Message;
use App\Domain\Auth\ValueObject\Email;
use App\Domain\Message\Exception\MessageException;
use App\Domain\Message\ValueObject\Recipient;
use App\Domain\Message\ValueObject\RecipientCollection;
use App\Domain\Message\ValueObject\Template;
use Ramsey\Uuid\UuidInterface;
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
    public function sendRegistrationSuccessMessage(UuidInterface $uuid, Email $email): void
    {
        try {
            $recipients = (new RecipientCollection())
                ->add(new Recipient(Recipient::TYPE_EMAIL, $email->toString()))
                ->get();
            $template = new Template(Template::REGISTRATION_SUCCESS, 'en');

            $message = new Message($uuid->toString(), $recipients, $template->toArray());
            $this->messageBus->dispatch($message);
        } catch (Throwable $exception) {
            throw MessageException::sendRegistrationSuccessMessageProblem($exception->getMessage());
        }
    }

    /**
     * @throws MessageException
     */
    public function sendPasswordForgotCodeMessage(string $contact, string $code): void
    {
        try {
            $recipients = (new RecipientCollection())->add(Recipient::autoDetectType($contact))->get();
            $template = new Template(Template::REGISTRATION_SUCCESS, 'en', ['code' => $code]);

            $message = new Message('????', $recipients, $template->toArray());
            $this->messageBus->dispatch($message);
        } catch (Throwable $exception) {
            throw MessageException::sendPasswordForgotCodeMessageProblem($exception->getMessage());
        }
    }
}
