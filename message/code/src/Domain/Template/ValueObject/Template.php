<?php

declare(strict_types=1);

namespace App\Domain\Template\ValueObject;

use Immutable\ValueObject\ValueObject;
use InvalidArgumentException;
use function json_encode;
use Throwable;

final class Template extends ValueObject
{
    public const DEFAULT_LANGUAGE = 'en';

    protected string $subject;
    protected string $body;
    protected array $variables = [];

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
        } catch (Throwable $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }

        return $this->with([
            'subject' => $subject,
            'body' => $body,
        ]);
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     */
    public function withVariables(Template $template, array $variables = []): Template
    {
        if (empty($variables)) {
            return $template;
        }

        return new self($template->subject(), vsprintf($template->body(), $variables));
    }

    public function toJson(): string
    {
        return json_encode([
            'subject' => $this->subject,
            'body' => $this->body,
            'variables' => $this->variables
        ], JSON_THROW_ON_ERROR, 512);
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
