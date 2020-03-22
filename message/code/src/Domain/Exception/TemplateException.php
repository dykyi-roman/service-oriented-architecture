<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class TemplateException extends Exception
{
    public static function notFoundTemplate(string $name, string $lang, string $type): self
    {
        return new static(sprintf('Template "%s" with type %s is not found for language "%s"', $name, $type, $lang));
    }

    public static function idIsInvalid(string $id): self
    {
        return new static(sprintf('uuid "%s" is invalid', $id));
    }

    public static function createTemplateProblem(string $name): self
    {
        return new static(sprintf('Template "%s" is not created', $name));
    }

    public static function updateTemplateProblem(string $name): self
    {
        return new static(sprintf('Template "%s" is not updated', $name));
    }

    public static function deleteTemplateProblem(string $name): self
    {
        return new static(sprintf('Template "%s" is not deleted', $name));
    }
}
