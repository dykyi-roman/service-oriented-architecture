<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Exception\TemplateException;
use App\Domain\Repository\TemplateReadRepositoryInterface;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\Template;
use Psr\SimpleCache\CacheInterface;

final class TemplateFinder
{
    private const TTL = 360;

    private TemplateReadRepositoryInterface $templateReadRepository;
    private CacheInterface $cache;

    public function __construct(TemplateReadRepositoryInterface $templateReadRepository, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->templateReadRepository = $templateReadRepository;
    }

    /**
     * @inheritDoc
     * @throws \Immutable\Exception\ImmutableObjectException
     * @throws TemplateException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function find(\stdClass $data, MessageType $type): Template
    {
        if (!$this->cache->has($data->name)) {
            $lang = $data->lang ?? Template::DEFAULT_LANGUAGE;
            $documentTemplate = $this->templateReadRepository->findTemplate($data->name, $type->toString(), $lang);
            if (null === $documentTemplate) {
                throw TemplateException::notFoundTemplate($data->name, $data->lang, $type->toString());
            }

            $template = new Template($documentTemplate->getSubject(), $documentTemplate->getContext());
            $template = $template->withVariables($template, $data->variables);

            $this->cache->set($data->name, $template, self::TTL);
        }

        return $this->cache->get($data->name);
    }
}
