<?php

declare(strict_types=1);

namespace App\Application\Template;

use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\Repository\TemplatePersistRepositoryInterface;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\ValueObject\Template;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ramsey\Uuid\UuidInterface;
use Throwable;

final class TemplateEditor
{
    private TemplatePersistRepositoryInterface $persistRepository;
    private LoggerInterface $logger;

    public function __construct(TemplatePersistRepositoryInterface $persistRepository, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->persistRepository = $persistRepository;
    }

    /**
     * @inheritDoc
     * @throws TemplateException
     */
    public function create(UuidInterface $id, Template $template, MessageType $type, string $name, string $lang): void
    {
        try {
            $this->persistRepository->create(
                $id->toString(),
                $name,
                $type->toString(),
                $lang,
                $template->subject(),
                $template->body());
        } catch (Throwable $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, ['error' => $exception->getMessage(),]);
            throw TemplateException::createTemplateProblem($name);
        }
    }

    /**
     * @inheritDoc
     * @throws TemplateException
     */
    public function update(string $id, Template $template): void
    {
        try {
            $this->persistRepository->edit($id, $template->subject(), $template->body());
        } catch (Throwable $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, ['error' => $exception->getMessage(),]);
            throw TemplateException::updateTemplateProblem($id);
        }
    }

    /**
     * @inheritDoc
     * @throws TemplateException
     */
    public function delete(string $id): void
    {
        try {
            $this->persistRepository->remove($id);
        } catch (Throwable $exception) {
            $msg = sprintf('%s::%s', substr(strrchr(__CLASS__, "\\"), 1), __FUNCTION__);
            $this->logger->error($msg, ['error' => $exception->getMessage(),]);
            throw TemplateException::deleteTemplateProblem($id);
        }
    }
}
