<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Application\Common\Service\JsonSchemaValidator;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @coversDefaultClass JsonSchemaValidator
 */
class JsonSchemaValidatorTest extends WebTestCase
{
    private const JSON_TEMPLATE = '{"userId":"%s","recipients":{"email":"%s", "phone":"%s"},"template":{"name":"%s","lang":"en","variables":["roman"]}}';

    /**
     * @covers ::validate
     */
    public function testValidateSuccess(): void
    {
        self::bootKernel();
        $projectDir = self::$container->get('kernel')->getProjectDir();
        $jsonSchemaDir = $projectDir.'/config/jsonschema/';

        $bag = $this->createMock(ParameterBagInterface::class);
        $bag->method('get')->willReturn($jsonSchemaDir);

        $faker = Factory::create();
        $json = sprintf(self::JSON_TEMPLATE, $faker->uuid, $faker->email, $faker->e164PhoneNumber, $faker->text);

        $schema = new JsonSchemaValidator($bag);
        $schema->validate(json_decode($json),'queue.json');

        $this->assertTrue(true);
    }
}
