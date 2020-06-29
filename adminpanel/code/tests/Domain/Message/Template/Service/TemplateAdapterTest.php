<?php

declare(strict_types=1);

namespace App\Tests\Domain\Message\Template\Service;

use App\Domain\Message\Template\Service\TemplateAdapter;
use App\Domain\Message\Template\ValueObject\Template;
use App\Infrastructure\HttpClient\ResponseDataExtractor;
use Faker\Factory;
use Psr\Http\Client\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass TemplateAdapter::class
 */
final class TemplateAdapterTest extends TestCase
{
    /**
     * @covers ::createTemplate
     */
    public function testCreateTemplate(): void
    {
        $faker = Factory::create();
        $template = new Template(
            $faker->name,
            'ua',
            'phone',
            $faker->text,
            $faker->text,
        );

        $response = new Response(
            200,
            [],
            json_encode(['status' => 'success', 'data' => ['id' => $faker->uuid]])
        );

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willReturn($response);

        $adapter = new TemplateAdapter($clientMock, new ResponseDataExtractor(), 'test-host');
        $response = $adapter->createTemplate($template);

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->hasError());
    }

    /**
     * @covers ::getAllTemplates
     */
    public function testAllTemplate(): void
    {
        $faker = Factory::create();
        $response = new Response(
            200,
            [],
            json_encode(
                [
                    'status' => 'success',
                    'data' => [
                        "id" => $faker->uuid,
                        "name" => $faker->name,
                        "type" => "phone",
                        "lang" => "en",
                        "subject" => $faker->text,
                        "context" => $faker->text
                    ]
                ]
            )
        );

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willReturn($response);

        $adapter = new TemplateAdapter($clientMock, new ResponseDataExtractor(), 'test-host');
        $response = $adapter->getAllTemplates();

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->hasError());
    }

    /**
     * @covers ::deleteTemplateById
     */
    public function testDeleteTemplateById(): void
    {
        $faker = Factory::create();
        $response = new Response(
            200,
            [],
            json_encode(['status' => 'success', 'data' => []])
        );

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willReturn($response);

        $adapter = new TemplateAdapter($clientMock, new ResponseDataExtractor(), 'test-host');
        $response = $adapter->deleteTemplateById($faker->uuid);

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->hasError());
    }
}
