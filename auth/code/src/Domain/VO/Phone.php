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
            if (!$this->isValidateMobile($phone)) {
                throw new \InvalidArgumentException('Phone number is not correct');
            }
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }

        return $this->with([
            'phone' => $phone,
        ]);
    }

    private function isValidateMobile(string $value): bool
    {
        $filteredPhoneNumber = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        $phoneToCheck = str_replace('-', '', $filteredPhoneNumber);
        $tmp = \strlen($phoneToCheck);

        return !($tmp < 10 || $tmp > 14);
    }
}
