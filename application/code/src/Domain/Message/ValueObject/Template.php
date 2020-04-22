<?php

declare(strict_types=1);

namespace App\Domain\Message\ValueObject;

final class Template
{
    public const WELCOME = 'welcome';

    private string $name;
    private string $lang;
    private array $variables;

    public function __construct(string $name, string $lang, array $variables = [])
    {
        $this->name = $name;
        $this->lang = $lang;
        $this->variables = $variables;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'lang' => $this->lang,
            'variables' => $this->variables,
        ];
    }
}