<?php

declare(strict_types=1);

namespace App\Application\Template;

use App\Application\Common\Service\ExceptionLogger;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\Document\Template;
use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\Repository\ReadTemplateRepositoryInterface;
use App\Domain\Template\Repository\WriteTemplateRepositoryInterface;
use App\Domain\Template\ValueObject\Template as TemplateVO;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Throwable;

final class TemplateEditor
{
    private LoggerInterface $logger;
    private WriteTemplateRepositoryInterface $writeTemplateRepository;
    private ReadTemplateRepositoryInterface $readTemplateRepository;

    public function __construct(
        WriteTemplateRepositoryInterface $writeTemplateRepository,
        ReadTemplateRepositoryInterface $readTemplateRepository,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->writeTemplateRepository = $writeTemplateRepository;
        $this->readTemplateRepository = $readTemplateRepository;
    }

    /**
     * @throws TemplateException
     */
    public function create(UuidInterface $id, TemplateVO $template, MessageType $type, string $name, string $lang): void
    {
        try {
            $this->writeTemplateRepository->create(
                $id->toString(),
                $name,
                $type->toString(),
                $lang,
                $template->subject(),
                $template->body()
            );
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            throw TemplateException::createTemplateProblem($name);
        }
    }

    /**
     * @throws TemplateException
     */
    public function update(string $id, TemplateVO $template): void
    {
        try {
            $this->writeTemplateRepository->edit($id, $template->subject(), $template->body());
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            throw TemplateException::updateTemplateProblem($id);
        }
    }

    /**
     * @throws TemplateException
     */
    public function delete(string $id): void
    {
        try {
            $this->writeTemplateRepository->remove($id);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            throw TemplateException::deleteTemplateProblem($id);
        }
    }

    /**
     * @throws TemplateException
     */
    public function getOneById(string $id): Template
    {
        try {
            $template = $this->readTemplateRepository->findById($id);
            if (null === $template) {
                throw new RuntimeException('Template is not found');
            }

            return $template;
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            throw TemplateException::notFoundTemplateById($id);
        }
    }

    public function getAll(): array
    {
        return $this->readTemplateRepository->all();
    }
}
