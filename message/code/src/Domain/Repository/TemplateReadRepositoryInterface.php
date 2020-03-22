<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Document\Template;

interface TemplateReadRepositoryInterface
{
    public function findTemplate(string $name, string $type, string $lang): ?Template;
}