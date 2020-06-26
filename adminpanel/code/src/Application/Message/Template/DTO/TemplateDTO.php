<?php

declare(strict_types=1);

namespace App\Application\Message\Template\DTO;

final class TemplateDTO
{
    public string $id;
    public string $name;
    public string $type;
    public string $lang;
    public string $subject;
    public string $context;

    public function __construct(array $template)
    {
        $this->id = $template['id'];
        $this->name = $template['name'];
        $this->type = $template['type'];
        $this->lang = $template['lang'];
        $this->subject = $template['subject'];
        $this->context = $template['context'];
    }
}
