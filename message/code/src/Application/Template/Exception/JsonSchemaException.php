<?php

declare(strict_types=1);

namespace App\Application\Template\Exception;

use JsonException;

final class JsonSchemaException extends JsonException
{
    public static function decodeProblem(): self
    {
        return new static('Template request is not decoded');
    }

    public static function validationProblem(): self
    {
        return new static('Template request is not validated');
    }
}
