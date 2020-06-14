<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Request\UserUpdateRequest;
use App\Domain\User\ValueObject\FullName;
use Ramsey\Uuid\UuidInterface;

/**
 * @see UserUpdateHandler::class
 */
final class UserUpdateCommand
{
    public UuidInterface $id;
    public FullName $fullName;
    public bool $active;

    public function __construct(UuidInterface $id, UserUpdateRequest $request)
    {
        $this->id = $id;
        $this->fullName = $request->getFullName();
        $this->active = $request->isActive();
    }
}
