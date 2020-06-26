<?php

declare(strict_types=1);

namespace App\UI\Http\Rest;

use App\Application\Common\Error;
use App\Application\Template\Transformer\TemplatesToArrayTransformer;
use App\Application\Template\Request\CreateTemplateRequest;
use App\Application\Template\Request\TemplateRequest;
use App\Application\Template\Request\UpdateTemplateRequest;
use App\Application\Template\TemplateEditor;
use App\Domain\Sender\ValueObject\MessageType;
use App\Domain\Template\Exception\TemplateException;
use App\Domain\Template\ValueObject\Template;
use Exception;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @OA\Tag(name="Admin")
 */
final class AdminController extends ApiController
{
    private TemplateEditor $templateEditor;

    public function __construct(TemplateEditor $templateEditor)
    {
        $this->templateEditor = $templateEditor;
    }

    /**
     * @OA\Post(
     *     tags={"Admin"},
     *     path="/api/admin/templates/",
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
     * @Route("/api/admin/templates/", methods={"POST"}, name="api.admin.template.create")
     */
    public function create(CreateTemplateRequest $request): JsonResponse
    {
        try {
            $id = Uuid::uuid4();
            $lang = '' === $request->lang() ? Template::DEFAULT_LANGUAGE : $request->lang();
            $template = new Template($request->subject(), $request->context());
            $type = new MessageType($request->type());
            $this->templateEditor->create($id, $template, $type, $request->name(), $lang);

            return $this->respondCreated(['id' => $id]);
        } catch (TemplateException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        } catch (Exception $exception) {
            return $this->respondValidationError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Put(
     *     tags={"Admin"},
     *     path="/api/admin/templates/{id}",
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
     * @Route("/api/admin/templates/{id}", methods={"PUT"}, name="api.admin.template.update")
     */
    public function update(UpdateTemplateRequest $request): JsonResponse
    {
        try {
            $this->templateEditor->update($request->id(), new Template($request->subject(), $request->context()));

            return $this->respondWithSuccess();
        } catch (TemplateException | Exception $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/admin/templates/{id}",
     *     summary="Get template by id",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/admin/templates/{id}", methods={"GET"}, name="api.admin.template.one")
     */
    public function getTemplate(TemplateRequest $request): JsonResponse
    {
        try {
            $template = $this->templateEditor->getOneById($request->getId());

            return $this->respondWithSuccess($template->toArray());
        } catch (TemplateException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/admin/templates",
     *     summary="Get all templates",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */

    /**
     * @Route("/api/admin/templates", methods={"GET"}, name="api.admin.template.all")
     */
    public function getTemplates(): JsonResponse
    {
        try {
            $templates = $this->templateEditor->getAll();

            return $this->respondWithSuccess(TemplatesToArrayTransformer::transform($templates));
        } catch (Throwable $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Admin"},
     *     path="/api/admin/templates/{id}",
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
     * @Route("/api/admin/templates/{id}", methods={"DELETE"}, name="api.admin.template.delete")
     */
    public function delete(TemplateRequest $request): JsonResponse
    {
        try {
            $this->templateEditor->delete($request->getId());

            return $this->respondWithSuccess();
        } catch (TemplateException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }
}
