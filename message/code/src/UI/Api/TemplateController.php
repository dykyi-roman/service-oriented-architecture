<?php

declare(strict_types=1);

namespace App\UI\Api;

use App\Application\Template\Request\CreateTemplateRequest;
use App\Application\Template\Request\DeleteTemplateRequest;
use App\Application\Template\Request\UpdateTemplateRequest;
use App\Domain\Sender\Exception\MessageException;
use App\Domain\Template\Exception\TemplateException;
use App\Application\Template\TemplateEditor;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\ValueObject\Template;
use Exception;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Message API", version="1.0.2")
 * @OA\Tag(name="Template")
 */
final class TemplateController extends ApiController
{
    private TemplateEditor $templateEditor;

    public function __construct(TemplateEditor $templateEditor)
    {
        $this->templateEditor = $templateEditor;
    }

    /**
     * @OA\Post(
     *     tags={"Template"},
     *     path="/api/template/",
     *     summary="Create new template",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="lang",
     *                  type="string",
     *                  description="ua|en|ru|..."
     *              ),
     *              @OA\Property(
     *                  property="type",
     *                  type="string",
     *                  description="phone|email"
     *              ),
     *              @OA\Property(
     *                  property="subject",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="context",
     *                  type="string",
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/template/", methods={"POST"}, name="template_create")
     */
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

    /**
     * @OA\Put(
     *     tags={"Template"},
     *     path="/api/template/{id}",
     *     summary="Update exist template by id",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="subject",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="context",
     *                  type="string",
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/template/{id}", methods={"PUT"}, name="template_update")
     */
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

    /**
     * @OA\Delete(
     *     tags={"Template"},
     *     path="/api/template/{id}",
     *     summary="Delete template by id",
     *     @OA\Parameter(
     *          name="query",
     *          in="query",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="id",
     *                  type="string",
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/template/{id}", methods={"DELETE"}, name="template_delete")
     */
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
