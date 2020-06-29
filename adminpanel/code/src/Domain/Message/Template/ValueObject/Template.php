<?php

declare(strict_types=1);

namespace App\Domain\Message\Template\ValueObject;

final class Template
{
    public const TYPE_PHONE = 'phone';
    public const TYPE_EMAIL = 'email';

    public string $name;
    public string $lang;
    public string $type;
    public string $subject;
    public string $context;

    public function __construct(string $name, string $lang, string $type, string $subject, string $context)
    {
        $this->name = $name;
        $this->lang = $lang;
        $this->type = $type;
        $this->subject = $subject;
        $this->context = $context;

        //todo:: add some validation here
    }
}
