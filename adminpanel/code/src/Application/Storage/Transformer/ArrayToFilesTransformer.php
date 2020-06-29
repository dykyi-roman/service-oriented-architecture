<?php

declare(strict_types=1);

namespace App\Application\Storage\Transformer;

use App\Application\Storage\DTO\FileDTO;

class ArrayToFilesTransformer
{
    public static function transform(array $data): array
    {
        return array_map((fn(array $file) => new FileDTO($file)), $data);
    }
}
