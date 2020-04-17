<?php

declare(strict_types=1);

namespace App\UI\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use function OpenApi\scan;
use Symfony\Component\HttpFoundation\Response;

class SwaggerController extends Controller
{
    public function index()
    {
        return redirect(env('APP_URL') . '/swagger/index.html');
    }

    public function update(): Response
    {
        echo scan(sprintf('%s/src/UI/Http/Controllers', base_path()))->toYaml();

        return new Response();
    }
}
