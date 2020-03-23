<?php

declare(strict_types=1);

namespace App\Application\Request;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use App\Application\Service\JsonSchemaValidator;
use Symfony\Component\HttpFoundation\RequestStack;

final class UpdateTemplateRequest
{
    private const SCHEMA = 'template-update.json';

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
     * @return string
     * @throws InvalidArgumentException
     */
    public function getId(): string
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

    /**
     * @return resource|string
     */
    public function getContent()
    {
        $request = $this->requestStack->getCurrentRequest();

        return null === $request ? '' : $request->getContent();
    }
}
