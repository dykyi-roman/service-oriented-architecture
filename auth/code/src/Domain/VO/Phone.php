<?php

declare(strict_types=1);

namespace App\Domain\VO;

use Immutable\ValueObject\ValueObject;

final class Phone extends ValueObject
{
    protected string $phone;

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function __construct(string $phone)
    {
        $this->withChanged($phone);
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function withChanged(string $phone): ValueObject
    {
        try {
            new NotEmpty($phone);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }

        return $this->with([
            'phone' => $phone,
        ]);
    }
}
