<?php

declare(strict_types=1);

namespace App\Domain\Template\Repository;

use App\Domain\Template\Document\Template;

interface ReadTemplateRepositoryInterface
{
    public function findTemplate(string $name, string $type, string $lang): ?Template;

    public function findById(string $id): ?Template;

    public function all(): array;
}
