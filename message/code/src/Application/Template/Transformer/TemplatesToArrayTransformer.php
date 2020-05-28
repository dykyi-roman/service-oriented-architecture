<?php

declare(strict_types=1);

namespace App\Application\Template\Transformer;

use App\Domain\Template\Document\Template;

final class TemplatesToArrayTransformer
{
    private function __construct()
    {
        //Mock __construct
    }

    public static function transform(array $templates): array
    {
        return array_map(fn(Template $template) => $template->toArray(), $templates);
    }
}
