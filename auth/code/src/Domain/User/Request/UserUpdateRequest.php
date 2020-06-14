<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use App\Domain\User\ValueObject\FullName;
use Immutable\Exception\ImmutableObjectException;
use Immutable\ValueObject\ValueObject;
use InvalidArgumentException;
use Throwable;

final class UserUpdateRequest extends ValueObject
{
    protected bool $active;
    protected FullName $fullName;

    /**
     * @inheritDoc
     *
     * @throws ImmutableObjectException
     */
    public function __construct(string $fullName, string $active)
    {
        $this->withChanged($fullName, $active === '1');
        parent::__construct();
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     * @throws ImmutableObjectException
     */
    public function withChanged(string $fullName, bool $active): ValueObject
    {
        try {
            return $this->with(
                [
                    'fullName' => FullName::fromFullName($fullName),
                    'active' => $active,
                ]
            );
        } catch (Throwable $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
