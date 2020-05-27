<?php

declare(strict_types=1);

namespace App\Domain\Template;

use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\Repository\ReadTemplateRepositoryInterface;
use App\Domain\Template\ValueObject\Template;
use App\Infrastructure\Cache\CacheInterface;
use App\Infrastructure\Metrics\MetricsInterface;
use Immutable\Exception\ImmutableObjectException;
use stdClass;

final class TemplateLoader
{
    private const TTL = 360;

    private CacheInterface $cache;
    private MetricsInterface $metrics;
    private ReadTemplateRepositoryInterface $readTemplateReadRepository;

    public function __construct(
        ReadTemplateRepositoryInterface $readTemplateReadRepository,
        CacheInterface $cache,
        MetricsInterface $metrics
    ) {
        $this->cache = $cache;
        $this->metrics = $metrics;
        $this->readTemplateReadRepository = $readTemplateReadRepository;
    }

    /**
     * @inheritDoc
     * @throws ImmutableObjectException
     * @throws TemplateException
     */
    public function load(stdClass $data, MessageType $type): Template
    {
        $this->metrics->startTiming('template_load');
        $cacheKey = $this->generateCacheKey($data, $type);
        if (!$this->cache->has($cacheKey)) {
            $lang = $data->lang ?? Template::DEFAULT_LANGUAGE;
            $documentTemplate = $this->readTemplateReadRepository->findTemplate($data->name, $type->toString(), $lang);
            if (null === $documentTemplate) {
                throw TemplateException::notFoundTemplate($data->name, $data->lang, $type->toString());
            }

            $template = new Template($documentTemplate->getSubject(), $documentTemplate->getContext());
            $template = $template->withVariables($template, $data->variables);

            $this->cache->set($cacheKey, serialize($template), self::TTL);
            $this->metrics->inc('template_load');
        }
        $this->metrics->endTiming('template_load', 1.0, ['type' => $type->toString()]);

        return unserialize($this->cache->get($cacheKey), []);
    }

    private function generateCacheKey(stdClass $stdClass, MessageType $type): string
    {
        return sprintf('%s-%s-%s', $stdClass->name, $stdClass->lang, $type->toString());
    }
}
