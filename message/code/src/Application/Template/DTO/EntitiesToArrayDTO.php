<?php

declare(strict_types=1);

namespace App\Application\Template\DTO;

use App\Domain\Template\Document\Template;

final class EntitiesToArrayDTO
{
    public static function convert(array $templates): array
    {
        return array_map(fn(Template $template) => $template->toArray(), $templates);
    }
}
