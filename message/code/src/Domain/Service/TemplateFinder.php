<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Exception\TemplateException;
use App\Domain\Repository\TemplateRepositoryInterface;
use App\Domain\ValueObject\Template;

final class TemplateFinder
{
    private TemplateRepositoryInterface $templateRepository;

    public function __construct(TemplateRepositoryInterface $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws TemplateException
     */
    public function find(string $name, string $type, string $language): Template
    {
        $template = $this->templateRepository->findTemplate($name, $type, $language);
        if (null === $template) {
            throw TemplateException::notFoundTemplate($name);
        }

        return new Template($template->getSubject(), $template->getContext());
    }
}
