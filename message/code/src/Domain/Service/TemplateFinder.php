<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Exception\TemplateException;
use App\Domain\Repository\TemplateRepositoryInterface;
use App\Domain\ValueObject\Template;
use Psr\SimpleCache\CacheInterface;

final class TemplateFinder
{
    private TemplateRepositoryInterface $templateRepository;
    private CacheInterface $cache;

    public function __construct(TemplateRepositoryInterface $templateRepository, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws TemplateException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function find(string $name, string $type, string $language): Template
    {
        if (!$this->cache->has($name)) {
            $template = $this->templateRepository->findTemplate($name, $type, $language);
            if (null === $template) {
                throw TemplateException::notFoundTemplate($name);
            }

            $this->cache->set($name, new Template($template->getSubject(), $template->getContext()));
        }

        return $this->cache->get($name);
    }
}
