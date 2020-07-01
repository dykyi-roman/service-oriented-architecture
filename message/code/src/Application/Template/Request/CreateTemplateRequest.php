<?php

declare(strict_types=1);

namespace App\Application\Template\Request;

use App\Application\Common\Service\JsonSchemaValidator;
use App\Application\Template\Exception\JsonSchemaException;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateTemplateRequest
{
    private const SCHEMA = 'template-create.json';

    private object $content;
    private RequestStack $requestStack;

    /**
     * @throws JsonSchemaException
     */
    public function __construct(RequestStack $requestStack, JsonSchemaValidator $schemaValidator)
    {
        $this->requestStack = $requestStack;

        try {
            $this->content = json_decode($this->getContent(), false, 512, JSON_THROW_ON_ERROR);
            $schemaValidator->validate($this->content, self::SCHEMA);
        } catch (BadRequestHttpException $exception) {
            throw JsonSchemaException::validationProblem();
        } catch (Exception $exception) {
            throw JsonSchemaException::decodeProblem();
        }
    }

    /**
     * @return resource|string
     */
    private function getContent()
    {
        $request = $this->requestStack->getCurrentRequest();

        return null === $request ? '' : $request->getContent();
    }

    public function subject(): string
    {
        return $this->content->subject;
    }

    public function context(): string
    {
        return $this->content->context;
    }

    public function type(): string
    {
        return $this->content->type;
    }

    public function name(): string
    {
        return $this->content->name;
    }

    public function lang(): string
    {
        return $this->content->lang;
    }
}
