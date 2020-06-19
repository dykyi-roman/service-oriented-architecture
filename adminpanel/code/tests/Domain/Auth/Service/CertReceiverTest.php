<?php

declare(strict_types=1);

namespace App\Tests\Domain\Auth\Service;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Service\CertReceiver;
use App\Infrastructure\HttpClient\ResponseDataExtractor;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @coversDefaultClass CertReceiver
 */
final class CertReceiverTest extends TestCase
{
    /**
     * @covers ::downloadPublicKey
     */
    public function testDownloadPublicKey(): void
    {
        $response = new Response(200, [], json_encode(['data' => ['key' => 'value']]));

        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willReturn($response);

        $certReceiver = new CertReceiver($clientMock, new ResponseDataExtractor(), 'test-host');
        $result = $certReceiver->downloadPublicKey(tempnam(sys_get_temp_dir(), 'Tux'));

        $this->assertTrue($result);
    }

    /**
     * @covers ::downloadPublicKey
     */
    public function testRaiseExceptionWhenDownloadPublicKey(): void
    {
        $this->expectException(AuthException::class);

        $exception = AuthException::publicKeyIsNotUpdated('error');
        $clientMock = $this->createMock(ClientInterface::class);
        $clientMock->expects(self::once())->method('sendRequest')->willThrowException($exception);

        $certReceiver = new CertReceiver($clientMock, new ResponseDataExtractor(), 'test-host');
        $certReceiver->downloadPublicKey(tempnam(sys_get_temp_dir(), 'Tux'));
    }
}
