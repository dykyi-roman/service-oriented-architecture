<?php

declare(strict_types=1);

namespace App\UI\Http\Rest;

use App\Application\Common\Error;
use App\Application\Template\Request\CreateTemplateRequest;
use App\Application\Template\Request\DeleteTemplateRequest;
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

/**
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
            $this->templateEditor->update($request->id(), new Template($request->subject(), $request->context()));

            return $this->respondWithSuccess();
        } catch (TemplateException | Exception $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
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
        } catch (TemplateException $exception) {
            return $this->respondWithError(Error::create($exception->getMessage(), $exception->getCode()));
        }
    }
}
