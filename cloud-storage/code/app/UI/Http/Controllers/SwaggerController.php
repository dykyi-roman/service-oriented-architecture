<?php

declare(strict_types=1);

namespace App\UI\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

use function OpenApi\scan;

class SwaggerController extends BaseController
{
    public function index()
    {
        return redirect('/swagger/index.html');
    }

    public function update(): Response
    {
        echo (scan(sprintf('%s/app/UI/Http/Controllers', base_path())))->toYaml();

        return new Response();
    }
}
