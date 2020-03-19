<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Immutable\ValueObject\ValueObject;

final class Template extends ValueObject
{
    protected string $subject;
    protected string $body;

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function __construct(string $subject, string $body)
    {
        $this->withChanged($subject, $body);
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function withChanged(string $subject, string $body): ValueObject
    {
        try {
            new NotEmpty($subject);
            new NotEmpty($body);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }

        return $this->with([
            'subject' => $subject,
            'body' => $body
        ]);
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function body(): string
    {
        return $this->body;
    }
}
