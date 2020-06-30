<?php

declare(strict_types=1);

namespace App\Tests\Domain\Storage\Service;

use App\Domain\Storage\Response\ApiResponseInterface;
use App\Domain\Storage\Service\StorageAdapter;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\HttpClient\ResponseDataExtractor;
use Psr\Http\Client\ClientInterface;
use GuzzleHttp\Psr7\Response;

/**
 * @coversDefaultClass StorageAdapter::class
 */
final class StorageAdapterTest extends TestCase
{
    /**
     * @covers ::downloadFile
     */
    public function testDownloadFile(): void
    {
        $faker = Factory::create();
        $response = new Response(
            200,
            [],
            json_encode(
                [
                    'status' => ApiResponseInterface::STATUS_SUCCESS,
                    "data" =>
                        [
                            "adapter" => "file-storage",
                            "payload" => [
                                "url" => $faker->url
                            ]
                        ]
                ],
                JSON_THROW_ON_ERROR
            )
        );

        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willReturn($response);

        $adapter = new StorageAdapter($client, new ResponseDataExtractor(), 'test-host');
        $response = $adapter->downloadFile('test-file-path');

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->hasError());
    }

    /**
     * @covers ::uploadFile
     */
    public function testUploadFile(): void
    {
        $faker = Factory::create();
        $response = new Response(
            200,
            [],
            json_encode(
                [
                    'status' => ApiResponseInterface::STATUS_SUCCESS,
                    "data" =>
                        [
                            "adapter" => "file-storage",
                            "payload" => [
                                "url" => $faker->url
                            ]
                        ]
                ],
                JSON_THROW_ON_ERROR
            )
        );

        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willReturn($response);

        $adapter = new StorageAdapter($client, new ResponseDataExtractor(), 'test-host');
        $response = $adapter->uploadFile(tempnam(sys_get_temp_dir(), 'Tux'), 'jpeg');

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->hasError());
    }
}
