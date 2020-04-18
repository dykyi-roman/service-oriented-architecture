<?php

declare(strict_types=1);

namespace App\UI\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class WebController extends Controller
{
    public function storage(Request $request): BinaryFileResponse
    {
        $file = storage_path() . '/app/' . $request->get('path', '');

        return new BinaryFileResponse($file);
    }

    public function download(Request $request): BinaryFileResponse
    {
        $file = storage_path() . '/app/' . $request->get('path');
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}
