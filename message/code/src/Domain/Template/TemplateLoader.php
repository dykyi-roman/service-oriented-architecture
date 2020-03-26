<?php

declare(strict_types=1);

namespace App\Domain\Template;

use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\Repository\TemplateReadRepositoryInterface;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\ValueObject\Template;
use Psr\SimpleCache\CacheInterface;
use stdClass;

final class TemplateLoader
{
    private const TTL = 360;

    private CacheInterface $cache;
    private TemplateReadRepositoryInterface $templateReadRepository;

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
    public function load(stdClass $data, MessageType $type): Template
    {
        $cacheKey = $this->generateCacheKey($data, $type);
        if (!$this->cache->has($cacheKey)) {
            $lang = $data->lang ?? Template::DEFAULT_LANGUAGE;
            $documentTemplate = $this->templateReadRepository->findTemplate($data->name, $type->toString(), $lang);
            if (null === $documentTemplate) {
                throw TemplateException::notFoundTemplate($data->name, $data->lang, $type->toString());
            }

            $template = new Template($documentTemplate->getSubject(), $documentTemplate->getContext());
            $template = $template->withVariables($template, $data->variables);

            $this->cache->set($cacheKey, $template, self::TTL);
        }

        return $this->cache->get($cacheKey);
    }

    private function generateCacheKey(stdClass $stdClass, MessageType $type): string
    {
        return sprintf('%s-%s-%s', $stdClass->name, $stdClass->lang, $type->toString());
    }
}
