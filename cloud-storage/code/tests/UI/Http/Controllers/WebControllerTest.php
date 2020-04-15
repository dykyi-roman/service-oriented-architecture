<?php

declare(strict_types=1);

use App\UI\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @coversDefaultClass WebController
 */
class WebControllerTest extends TestCase
{
    private const TEST_FILE = '.gitignore';
    /**
     * @covers ::storage
     */
    public function testStorageResponseSuccess(): void
    {
        $controller = new WebController();
        $request = new Request([], ['path' => self::TEST_FILE]);

        $result = $controller->storage($request);

        $this->assertInstanceOf(BinaryFileResponse::class, $result);
    }

    /**
     * @covers ::download
     */
    public function testDownloadResponseSuccess(): void
    {
        $controller = new WebController();
        $request = new Request([], ['path' => self::TEST_FILE]);

        $result = $controller->download($request);
        $this->assertInstanceOf(BinaryFileResponse::class, $result);
        $this->assertSame($result->headers->get('content-disposition'), 'attachment; filename=.gitignore');
    }
}
