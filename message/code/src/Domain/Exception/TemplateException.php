<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class TemplateException extends Exception
{
    public static function notFoundTemplate(string $name): self
    {
        return new static(sprintf('Template "%s" not found', $name));
    }
}
