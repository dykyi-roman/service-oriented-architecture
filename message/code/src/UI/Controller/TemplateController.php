<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Application\Request\CreateTemplateRequest;
use App\Application\Request\DeleteTemplateRequest;
use App\Application\Request\UpdateTemplateRequest;
use App\Domain\Exception\MessageException;
use App\Domain\Exception\TemplateException;
use App\Application\Service\AdminPanelTemplateEditor;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\Template;
use Exception;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TemplateController extends ApiController
{
    private AdminPanelTemplateEditor $templateEditor;

    public function __construct(AdminPanelTemplateEditor $templateEditor)
    {
        $this->templateEditor = $templateEditor;
    }

    public function create(CreateTemplateRequest $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

            $id = Uuid::uuid4();
            $lang = $content->lang ?? Template::DEFAULT_LANGUAGE;
            $template = new Template($content->subject, $content->context);
            $type = new MessageType($content->type);
            $this->templateEditor->create($id, $template, $type, $content->name, $lang);

            return $this->respondCreated(['id' => $id]);
        } catch (TemplateException $exception) {
            return $this->respondWithErrors($exception->getMessage());
        } catch (Exception | ImmutableObjectException | MessageException | InvalidValueException $exception) {
            return $this->respondValidationError($exception->getMessage());
        }
    }

    public function update(UpdateTemplateRequest $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);
            $request->validate($request->getContent());
            $this->templateEditor->update($request->getId(), new Template($content->subject, $content->context));

            return $this->respondWithSuccess();
        } catch (TemplateException | ImmutableObjectException| Exception $exception) {
            return $this->respondWithErrors($exception->getMessage());
        }
    }

    public function delete(DeleteTemplateRequest $request): JsonResponse
    {
        try {
            $this->templateEditor->delete($request->getId());

            return $this->respondWithSuccess();
        } catch (InvalidArgumentException | TemplateException $exception) {
            return $this->respondWithErrors($exception->getMessage());
        }
    }
}
