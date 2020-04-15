<?php

declare(strict_types=1);

use App\Domain\Service\ExistAdaptersFinder;

/**
 * @coversDefaultClass ExistAdaptersFinder
 */
class ExistAdaptersFinderTest extends TestCase
{
    /**
     * @covers ::supported
     */
    public function testSupportedAdapterSuccess(): void
    {
        $adapters = (new ExistAdaptersFinder())->supported();

        $this->assertIsArray($adapters);
        $this->assertTrue(count($adapters) > 0);
    }
}
