<?php

declare(strict_types=1);

namespace App\Infrastructure\Clients;

use App\Domain\Sender\MessageInterface;
use App\Domain\Sender\MessageSenderInterface;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @see https://github.com/PHPMailer/PHPMailer
 */
final class PHPMailerClient implements MessageSenderInterface
{
    private PHPMailer $mailer;
    private LoggerInterface $logger;

    public function __construct(PHPMailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function send(MessageInterface $message): bool
    {
        try {
            $this->setServerSetting();
            $this->seRecipients($message);
            $this->setContent($message);
            $this->mailer->send();

            return true;
        } catch (Throwable $exception) {
            $this->logger->error('Message::PHPMailerClient', [
                'error' => $exception->getMessage(),
                'ErrorInfo' => $this->mailer->ErrorInfo,
            ]);

            return false;
        }
    }

    private function setServerSetting(): void
    {
        $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mailer->isSMTP();
        $this->mailer->Host = (string)getenv('SMTP_SERVER');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = (string)getenv('SMTP_USER_NAME');
        $this->mailer->Password = (string)getenv('SMTP_USER_PASSWORD');
        // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $this->mailer->Port = 587;
    }

    /**
     * @param MessageInterface $message
     *
     * @throws Exception
     */
    private function seRecipients(MessageInterface $message): void
    {
        $this->mailer->setFrom($message->recipients()->sender()->toString());
        $this->mailer->addAddress($message->recipients()->recipient()->toString());
    }

    private function setContent(MessageInterface $message): void
    {
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $message->template()->subject();
        $this->mailer->Body = $message->template()->body();
        // 'This is the body in plain text for non-HTML mail clients'
        $this->mailer->AltBody = $message->template()->body();
    }
}
