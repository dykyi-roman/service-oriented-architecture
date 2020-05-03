<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use function OpenApi\scan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SwaggerController extends AbstractController
{
    /**
     * @Route(path="/", name="swagger_index")
     */
    public function index(): RedirectResponse
    {
        return $this->redirect('/swagger/index.html');
    }

    /**
     * @Route(path="/swagger/update", name="web.swagger.update")
     */
    public function update(ParameterBagInterface $bag): Response
    {
        echo scan($bag->get('PROJECT_DIR'))->toYaml();

        return new Response();
    }
}
