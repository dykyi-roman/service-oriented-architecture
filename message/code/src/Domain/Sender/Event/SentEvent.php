<?php

declare(strict_types=1);

namespace App\Domain\Sender\Event;

use App\Domain\Template\ValueObject\Template;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @see PersistMessageSubscriber::calss
 */
class SentEvent extends Event
{
    private string $userId;

    private Template $template;

    public function __construct(string $userId, Template $template)
    {
        $this->userId = $userId;
        $this->template = $template;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTemplate(): Template
    {
        return $this->template;
    }
}
