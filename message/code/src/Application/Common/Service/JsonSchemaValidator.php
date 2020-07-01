<?php

declare(strict_types=1);

namespace App\Application\Common\Service;

use JsonException;
use JsonSchema\Validator;
use stdClass;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class JsonSchemaValidator
{
    private string $jsonSchemaPatch;

    public function __construct(ParameterBagInterface $bag)
    {
        $this->jsonSchemaPatch = $bag->get('JSON_SCHEME_PATH');
    }

    /**
     * @param stdClass $json
     * @param string   $schema
     *
     * @throws JsonException
     */
    public function validate(stdClass $json, string $schema): void
    {
        $validator = new Validator();
        $validator->check($json, (object)['$ref' => 'file://' . $this->jsonSchemaPatch . $schema]);
        if ($validator->isValid()) {
            return;
        }

        $errors = '';
        foreach ($validator->getErrors() as $error) {
            $errors .= \sprintf("[%s] %s\n", $error['property'], $error['message']);
        }

        throw new JsonException('JSON does not validate. Violations: ' . $errors);
    }
}
