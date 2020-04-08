<?php

declare(strict_types=1);

namespace App\Application\Template\Request;

use App\Application\Template\Exception\JsonSchemaException;
use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Application\JsonSchemaValidator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class UpdateTemplateRequest
{
    private const SCHEMA = 'template-update.json';

    private object $content;
    private RequestStack $requestStack;

    /**
     * @inheritDoc
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

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function id(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return '';
        }

        $id = $request->get('id', '');
        if ('' === $id || !UUid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('uuid "%s" is invalid', $id));
        }

        return $id;
    }

    public function subject(): string
    {
        return $this->content->subject;
    }

    public function context(): string
    {
        return $this->content->context;
    }
}
