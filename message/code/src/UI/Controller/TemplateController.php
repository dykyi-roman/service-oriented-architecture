<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Domain\Exception\MessageException;
use App\Domain\Exception\TemplateException;
use App\Application\Service\AdminPanelTemplateEditor;
use App\Domain\ValueObject\MessageType;
use App\Domain\ValueObject\Template;
use Exception;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class TemplateController extends ApiController
{
    private AdminPanelTemplateEditor $templateEditor;

    public function __construct(AdminPanelTemplateEditor $templateEditor)
    {
        $this->templateEditor = $templateEditor;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

            $id = Uuid::uuid4();
            $lang = $data->lang ?? Template::DEFAULT_LANGUAGE;
            $template = new Template($data->subject, $data->context);
            $type = new MessageType($data->type);
            $this->templateEditor->create($id, $template, $type, $data->name, $lang);

            return $this->respondCreated(['id' => $id]);
        } catch (TemplateException $exception) {
            return $this->respondWithErrors($exception->getMessage());
        } catch (Exception | ImmutableObjectException | MessageException | InvalidValueException $exception) {
            return $this->respondValidationError($exception->getMessage());
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $id = $request->get('id');
            if (!UUid::isValid($request->get('id'))) {
                throw TemplateException::idIsInvalid($id);
            }

            $data = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);
            $this->templateEditor->update($id, new Template($data->subject, $data->context));

            return $this->respondWithSuccess();
        } catch (TemplateException | ImmutableObjectException| Exception $exception) {
            return $this->respondWithErrors($exception->getMessage());
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $id = $request->get('id');
            if (!UUid::isValid($request->get('id'))) {
                throw TemplateException::idIsInvalid($id);
            }

            $this->templateEditor->delete($id);

            return $this->respondWithSuccess();
        } catch (TemplateException $exception) {
            return $this->respondWithErrors($exception->getMessage());
        }
    }
}
