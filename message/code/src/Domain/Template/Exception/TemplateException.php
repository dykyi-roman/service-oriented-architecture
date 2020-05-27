<?php

declare(strict_types=1);

namespace App\Domain\Template\Exception;

use Exception;

class TemplateException extends Exception
{
    public static function notFoundTemplate(string $name, string $lang, string $type): self
    {
        $message = sprintf('Template "%s" with type %s is not found for language "%s"', $name, $type, $lang);
        return new static($message, 4300);
    }

    public static function createTemplateProblem(string $name): self
    {
        return new static(sprintf('Template "%s" is not created', $name), 4301);
    }

    public static function updateTemplateProblem(string $name): self
    {
        return new static(sprintf('Template "%s" is not updated', $name), 4302);
    }

    public static function deleteTemplateProblem(string $name): self
    {
        return new static(sprintf('Template "%s" is not deleted', $name), 4303);
    }

    public static function notFoundTemplateById(string $id): self
    {
        return new static(sprintf('Template "%s" is not found', $id), 4304);
    }
}
