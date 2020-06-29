<?php

declare(strict_types=1);

namespace App\Application\Message\Template\DTO;

use App\Domain\Message\Template\ValueObject\Template;

final class TemplateCreateDTO
{
    public string $id;
    public string $name;
    public string $type;
    public string $lang;
    public string $subject;
    public string $context;

    public static function transform(Template $template): array
    {
        return [
            'name' => $template->name,
            'lang' => $template->lang,
            'type' => $template->type,
            'subject' => $template->subject,
            'context' => $template->context,
        ];
    }
}
