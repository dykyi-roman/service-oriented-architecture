<?php

declare(strict_types=1);

namespace App\Tests\Application\Security\Service;

use App\Application\Security\Service\Guard;
use App\Domain\Auth\Exception\AuthException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Application\Security\Service\Guard
 */
final class GuardTest extends TestCase
{
    /**
     * @covers ::verify
     */
    public function testVerifyWhenFileNotExist(): void
    {
        $this->expectException(AuthException::class);

        $guard = new Guard();
        $guard->verify('token', 'file');
    }

    /**
     * @covers ::verify
     */
    public function testVerifyWhenPublicKeyHasWrongContext(): void
    {
        $this->expectException(AuthException::class);

        $tempFile = tempnam(sys_get_temp_dir(), 'Tux');
        file_put_contents($tempFile, 'test test test');

        $guard = new Guard();
        $guard->verify('token', $tempFile);
    }

    /**
     * @covers ::verify
     */
    public function testVerifyWhenTokenIsExpire(): void
    {
        $this->expectException(AuthException::class);

        $tempFile = tempnam(sys_get_temp_dir(), 'Tux');
        file_put_contents(
            $tempFile,
            '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA1qhinkKTquLqVUUzUvgc
/TKkqxXBLV7c7lQCA1h/O70NGlJ/wcNXeYEwMuXkl0r99Rcy2mqN+xTOStm5acLs
1s+CXKYOucTv1nO7dSTUwo0KFIE4OXvYuxzTVYQeB1AVeHI5VjuqVrfS9jzkFdLx
2NlZ0k25X20ZcsTLFqnQpzI6eM24hLx4T1WaiEDnsJsM9d992vXOw6lyBUrXqFfH
NYJ3767td8kRFESrgLuCVOx6NvuDGj4VHVISNypO1Qax4Ao/aeDZ4+jQOvk5yQmw
uvJn6bX+iAdGIWYXnob4i+LWNFw7LpTuPx5dJEd7qsqtPVORoZUBPqlOFeSraj6e
L5ZE7CmLHT4MdSr4vACrxsuTSMcxxdM073Kd0ZUyY+xzVzg1YJvhtcqfBs+jjjQ7
lnIAxjLmMXh+dSTZ+WR2kaUVYOF7Sw7/xFo+U/F9n9m8UJbqE91UYXTwkRsF+NbK
3naMHf73qvw/JQ5wcxBUq6hyEk6+iqdjnT83oZrEt/qAfb25gOp9pIwLeUa7NZZi
j5y8BFZSO7muKBIBR8+NJtt64sYJmmLGmC0Q90CVG9+7F1sDp/pF3bpyT8jR3maQ
B5V8BF2OZi3xlTrLZls5aSRONbpaeufOFmfPnxOM4Q9d0LbcNo2QSi72k+5Z0yxQ
N/wGU9iovsm2Vkb38LLbfncCAwEAAQ==
-----END PUBLIC KEY-----'
        );

        $guard = new Guard();
        $guard->verify(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTE4NTY0NDIsImV4cCI6MTU5MTg2MDA0Miwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImVtYWlsIjoidGVzdDE1OTE4NTYzODZAZ21haWwuY29tIiwicGhvbmUiOiIrMzgwOTM4OTgyNDQzIiwiaXAiOiIxNzIuMTguMC4xIiwiaWQiOiI4NWEzZGEwNC0wMzgwLTRiNWEtYTM2ZS01ZjYwZjAzNzgwMzAifQ.UYjzmLhta6sX-ewHWSuYyN8k2bA1hJApqQcMRiOIkhlHFN96TM9wRoTkbfvnaNrVbryHJPK-mhca69HTfHNW2lvBU3GP_TvtxxS0idCdRpo9Z8yqSxhhne9RKkQmPFdSms1t0EZ9fEOo8ypSARiXtrYtSfzzlM5x4MVq7H1SrrhWI3Jig1-cBgpF7MFwD7Q7ffOw8wje3Kb6nq92j53SCf_T7hIheVoYao8Nf8BHQIKBQMxu5jZxEB3IE_ypwV76E-z4mnzfjOZ1EuMawoyOwkAqAXmi1iZKh-3HRYD-V8clKIm3t6iPFB4HW8yZ1O-sXJdcIPq-odHLttGmBqui9og5D260cLVyrJPNK2dh_l99sPBBpCJBQ76JKMzUoo-0MwTpYyKI1P95JstF2Rw6Gs0fab8Ge8sN-ASCcJF4ISB3SUzDCpJn2TKB7yRgrEztyGJoFZ6Q17asgjicRbp6rWVwff4bgDEw2btw8lXZOKqCapXa885kAUWNQZNkXNrq40MdZMYdEvmJ4T6ucewL3FoKuScIZhwr8ouo98bM0x6Qi8keMFty3xMb3QIYtvKfjITRU0tQU1mfiXzxtRjndUmb5a3l29a7n3nXJPch7vXZZsTQxbJWqDvNkf4HRcUafp7jdx6343VoVPgB4DRkFahmAU0HKp1bOV6rie6rrs8',
            $tempFile
        );
    }
}
