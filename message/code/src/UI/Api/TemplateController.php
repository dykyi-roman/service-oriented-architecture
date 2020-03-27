<?php

declare(strict_types=1);

namespace App\UI\Api;

use App\Application\Sender\Message\MessageNotSent;
use App\Application\Sender\Message\MessageSent;
use App\Application\Template\Request\CreateTemplateRequest;
use App\Application\Template\Request\DeleteTemplateRequest;
use App\Application\Template\Request\UpdateTemplateRequest;
use App\Domain\Sender\Document\Sent;
use App\Domain\Sender\Exception\MessageException;
use App\Domain\Sender\Repository\SentPersistRepositoryInterface;
use App\Domain\Template\Exception\TemplateException;
use App\Application\Template\TemplateEditor;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\ValueObject\Template;
use Exception;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

final class TemplateController extends ApiController
{
    private TemplateEditor $templateEditor;

    public function __construct(TemplateEditor $templateEditor)
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

    public function test(MessageBusInterface $bus, SentPersistRepositoryInterface $sentPersistRepository): JsonResponse
    {
        $bus->dispatch(new MessageNotSent(Uuid::uuid4()->toString(), (new Template('1111', '33333'))->toJson(), 'errorr'));

        return new JsonResponse();
    }
}
