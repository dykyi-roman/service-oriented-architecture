<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands\Handler;

use App\Application\Auth\Commands\Command\ForgotPasswordCommand;
use App\Domain\Message\MessageAdapter;
use App\Domain\TemporaryCode\WriteTemporaryCodeInterface;

final class ForgotPasswordHandler
{
    private MessageAdapter $messageAdapter;
    private WriteTemporaryCodeInterface $temporaryCode;

    public function __construct(MessageAdapter $messageAdapter, WriteTemporaryCodeInterface $temporaryCode)
    {
        $this->messageAdapter = $messageAdapter;
        $this->temporaryCode = $temporaryCode;
    }

    /**
     * @throws \App\Domain\Message\Exception\MessageException
     */
    public function __invoke(ForgotPasswordCommand $command): void
    {
        $key = $this->filter($command->contact());
        $code = $this->temporaryCode->generate('password', $key);
        $this->messageAdapter->sendPasswordForgotCodeMessage($command->contact(), $code);
    }

    private function filter(string $value): string
    {
        return str_replace(['@', '.', '+', ' '], '', $value);
    }
}
