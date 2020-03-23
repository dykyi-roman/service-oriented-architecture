<?php

declare(strict_types=1);

namespace App\Application\Request;

use App\Application\Service\JsonSchemaValidator;
use App\Domain\Exception\TemplateException;
use Symfony\Component\HttpFoundation\RequestStack;

final class CreateTemplateRequest
{
    private const SCHEMA = 'template-create.json';

    private RequestStack $requestStack;
    private JsonSchemaValidator $schemaValidator;

    public function __construct(RequestStack $requestStack, JsonSchemaValidator $schemaValidator)
    {
        $this->requestStack = $requestStack;
        $this->schemaValidator = $schemaValidator;
    }

    public function validate(string $json): void
    {
        $body = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        $this->schemaValidator->validate($body, self::SCHEMA);
    }

    /**
     * @return resource|string
     */
    public function getContent()
    {
        $request = $this->requestStack->getCurrentRequest();

        return null === $request ? '' : $request->getContent();
    }
}
