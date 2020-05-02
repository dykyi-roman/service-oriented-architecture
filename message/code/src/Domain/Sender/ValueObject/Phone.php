<?php

namespace App\Domain\Sender\ValueObject;

use App\Domain\Sender\Exception\PhoneException;
use Immutable\ValueObject\ValueObject;

final class Phone extends ValueObject
{
    protected string $phone;

    /**
     * @throws \InvalidArgumentException
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function __construct(string $phone)
    {
        $this->withChanged($phone);
        parent::__construct();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function withChanged(string $phone): ValueObject
    {
        new NotEmpty($phone);
        if (!$this->isValidateMobile($phone)) {
            throw PhoneException::notCorrectPhoneNumber();
        }

        return $this->with([
            'phone' => $phone,
        ]);
    }

    public function toString(): string
    {
        return $this->phone;
    }

    public function __toString(): string
    {
        return $this->phone;
    }

    private function isValidateMobile(string $value): bool
    {
        $filteredPhoneNumber = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        $phoneToCheck = str_replace('-', '', $filteredPhoneNumber);
        $tmp = \strlen($phoneToCheck);

        return !($tmp < 10 || $tmp > 14);
    }
}
