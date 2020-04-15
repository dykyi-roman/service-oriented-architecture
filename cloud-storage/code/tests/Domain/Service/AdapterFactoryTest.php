<?php

declare(strict_types=1);

use App\Domain\Exception\AdapterException;
use App\Domain\Service\AdapterFactory;
use App\Domain\Service\ExistAdaptersFinder;
use App\Domain\StorageInterface;

/**
 * @coversDefaultClass AdapterFactory
 */
class AdapterFactoryTest extends TestCase
{
    /**
     * @covers ::create
     */
    public function testCreateWithEmptyAdapterFail(): void
    {
        $this->expectException(AdapterException::class);

        $factory = new AdapterFactory(new ExistAdaptersFinder());
        $factory->create('');
    }

    /**
     * @covers ::create
     */
    public function testCreateWithDelicateAdapterSuccess(): void
    {
        $factory = new AdapterFactory(new ExistAdaptersFinder());
        $adapters = $factory->create('in-memory,in-memory,in-memory');

        $this->assertCount(1, $adapters);
        $this->assertInstanceOf(StorageInterface::class, $adapters['in-memory']);
    }

    /**
     * @covers ::create
     */
    public function testCreateWithNotSupportedAdapterFail(): void
    {
        $adapter = 'not-exist-adapter';
        $this->expectExceptionMessage(sprintf(AdapterException::NO_SUPPORTED_MESSAGE, $adapter));

        $factory = new AdapterFactory(new ExistAdaptersFinder());
        $factory->create($adapter);
    }
}
