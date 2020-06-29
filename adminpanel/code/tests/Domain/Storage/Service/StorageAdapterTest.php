<?php

declare(strict_types=1);

namespace App\Tests\Domain\Storage\Service;

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
     * @covers ::searchFilesByUserId
     */
    public function testSearchFilesByUserId(): void
    {
        $faker = Factory::create();
        $response = new Response(
            200,
            [],
            json_encode(['status' => 'success', 'data' => ['id' => $faker->uuid]])
        );

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willReturn($response);

        $adapter = new StorageAdapter($clientMock, new ResponseDataExtractor(), 'test-host');
        $response = $adapter->searchFilesByUserId($faker->uuid);

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->hasError());
    }
}
