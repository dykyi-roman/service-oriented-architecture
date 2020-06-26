<?php

declare(strict_types=1);

namespace App\Domain\Message\Template\Exception;

use Exception;

class MessageException extends Exception
{
    public static function createTemplateError(string $message): MessageException
    {
        return new self(sprintf('Create template. Error: "%s"', $message), 2201);
    }

    public static function getAllTemplatesError(string $message): MessageException
    {
        return new self(sprintf('Get all template. Error: %s', $message), 2202);
    }

    public static function getTemplateError(string $id, string $message): MessageException
    {
        return new self(sprintf('Get template "%s". Error: %s', $id, $message), 2203);
    }

    public static function updateTemplateError(string $id, string $message): MessageException
    {
        return new self(sprintf('Update template "%s". Error: %s', $id, $message), 2204);
    }

    public static function deleteTemplateError(string $id, string $message): MessageException
    {
        return new self(sprintf('Delete template "%s". Error: %s', $id, $message), 2205);
    }
}
