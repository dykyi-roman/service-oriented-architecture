<?php

declare(strict_types=1);

namespace App\UI\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class WebController extends Controller
{
    private const APP_STORAGE_DIR = '/code/storage/app/';

    public function storage(Request $request): BinaryFileResponse
    {
        $file = self::APP_STORAGE_DIR . $request->get('path');

        return new BinaryFileResponse($file);
    }

    public function download(Request $request): BinaryFileResponse
    {
        $file = self::APP_STORAGE_DIR . $request->get('path');
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}
