<?php

namespace App\Domain\Template\ValueObject;

use Immutable\ValueObject\ValueObject;

final class NotEmpty extends ValueObject
{
    protected string $value = '';

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function __construct(string $value)
    {
        $this->withChanged($value);
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws \Immutable\Exception\InvalidValueException
     */
    public function withChanged(string $value): ValueObject
    {
        $this->assertValid($value);
        return $this->with([
            'value' => $value,
        ]);
    }

    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @param string $data
     * @throws \Immutable\Exception\InvalidValueException
     */
    protected function assertValid(string $data) : void
    {
        if (empty($data)) {
            $this->throwInvalidValueException(
                '$value',
                'is empty',
                $data
            );
        }
    }
}
