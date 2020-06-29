<?php

declare(strict_types=1);

namespace App\Application\Template\DTO;

use App\Domain\Template\Document\Template;

final class TemplateDTO
{
    public static function transform(Template $template): array
    {
        return [
            'id' => $template->getId(),
            'name' => $template->getName(),
            'type' => $template->getType(),
            'lang' => $template->getLang(),
            'subject' => $template->getSubject(),
            'context' => $template->getContext(),
        ];
    }
}
