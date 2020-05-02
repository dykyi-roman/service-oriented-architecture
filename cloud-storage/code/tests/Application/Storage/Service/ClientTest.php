<?php

declare(strict_types=1);

use App\Application\Service\Client;
use App\Domain\Storage\Exception\StorageConnectException;
use App\Domain\Storage\Service\AdapterFactory;
use App\Domain\Storage\Service\ExistAdaptersFinder;
use App\Domain\Storage\ValueObject\UploadFile;
use App\Infrastructure\Cache\InMemoryCache;
use App\Infrastructure\Metrics\InMemoryMetrics;
use Psr\Log\NullLogger;

/**
 * @coversDefaultClass Client
 */
class ClientTest extends TestCase
{
    /**
     * @covers ::init
     */
    public function testCallApiMethodWithoutCallFirstInitFail(): void
    {
        $this->expectException(StorageConnectException::class);
        $client = $this->createClient();

        $client->delete('some-path');
    }

    /**
     * @covers ::init
     */
    public function testClientWithoutError(): void
    {
        $client = $this->createClient();
        $client->init('in-memory');

        $this->assertTrue(true);
    }

    /**
     * @covers ::createFolder
     */
    public function testCreateFolderMethodSuccess(): void
    {
        $folderName = 'test-folder';
        $client = $this->createClient();
        $client->init('in-memory');

        $method = PrivateMethodTest::access(Client::class, 'execute');
        $result = $method->invokeArgs($client, ['createFolder', [$folderName]]);

        $this->assertIsArray($result);
        $this->assertEquals(['adapter' => 'in-memory', 'payload' => ['id' => $folderName]], $result[0]);
    }

    /**
     * @covers ::upload
     */
    public function testUploadMethodSuccess(): void
    {
        $client = $this->createClient();
        $client->init('in-memory');

        $uploadFile = new UploadFile('test-file', 'test');
        $method = PrivateMethodTest::access(Client::class, 'execute');
        $result = $method->invokeArgs($client, ['upload', [$uploadFile]]);

        $this->assertIsArray($result);
        $this->assertEquals([
            'adapter' => 'in-memory',
            'payload' => [
                'id' => 'test-id',
                'path' => $uploadFile->fileName(),
                'url' => '/' . $uploadFile->fileName()
            ]
        ], $result[0]);
    }

    /**
     * @covers ::download
     */
    public function testDownloadMethodSuccess(): void
    {
        $path = 'test.file';
        $client = $this->createClient();
        $client->init('in-memory');

        $method = PrivateMethodTest::access(Client::class, 'execute');
        $result = $method->invokeArgs($client, ['download', [$path]]);

        $this->assertIsArray($result);
        $this->assertEquals(['adapter' => 'in-memory', 'payload' => ['url' => $path]], $result[0]);
    }

    /**
     * @covers ::delete
     */
    public function testDeleteMethodSuccess(): void
    {
        $path = 'test/test.file';
        $client = $this->createClient();
        $client->init('in-memory');

        $method = PrivateMethodTest::access(Client::class, 'execute');
        $result = $method->invokeArgs($client, ['delete', [$path]]);

        $this->assertIsArray($result);
        $this->assertEquals(['adapter' => 'in-memory', 'payload' => []], $result[0]);
    }

    private function createClient(): Client
    {
        return new Client(
            new AdapterFactory(new ExistAdaptersFinder()),
            new InMemoryMetrics(),
            new InMemoryCache(),
            new NullLogger()
        );
    }
}
