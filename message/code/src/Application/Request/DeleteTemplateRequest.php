<?php

declare(strict_types=1);

namespace App\Application\Request;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RequestStack;

final class DeleteTemplateRequest
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
}
