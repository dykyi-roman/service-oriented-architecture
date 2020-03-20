<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

interface Recipients
{
    /**
     * @return Email|Phone
     */
    public function sender();

    /**
     * @return Email|Phone
     */
    public function recipient();
}
