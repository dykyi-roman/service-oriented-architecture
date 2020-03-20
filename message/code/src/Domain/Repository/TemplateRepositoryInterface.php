<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Document\Template;

interface TemplateRepositoryInterface
{
    public function findTemplate(string $name, string $type, string $language): ?Template;
}