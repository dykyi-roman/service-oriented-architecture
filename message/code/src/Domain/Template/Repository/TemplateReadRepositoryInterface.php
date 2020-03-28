<?php

declare(strict_types=1);

namespace App\Domain\Template\Repository;

use App\Domain\Template\Document\Template;

interface TemplateReadRepositoryInterface
{
    public function findTemplate(string $name, string $type, string $lang): ?Template;
}
