<?php

declare(strict_types=1);

namespace App\Application\Sender\Message;

use stdClass;

final class Message
{
    private stdClass $content;

    public function __construct(string $userId, array $recipients, array $template)
    {
        $content = new stdClass();
        $content->userId = $userId;
        $content->recipients = (object)$recipients;
        $content->template = (object)$template;

        $this->content = $content;
    }

    public function getContent(): stdClass
    {
        return $this->content;
    }
}
