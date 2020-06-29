<?php

declare(strict_types=1);

namespace App\Application\Message\Template\Transformer;

use App\Application\Message\Template\DTO\TemplateDTO;

class ArrayToTemplatesTransformer
{
    public static function transform(array $data): array
    {
        return array_map((fn(array $template) => new TemplateDTO($template)), $data);
    }
}
