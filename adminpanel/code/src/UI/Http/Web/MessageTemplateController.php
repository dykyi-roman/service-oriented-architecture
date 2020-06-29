<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Common\Exception\ExceptionLogger;
use App\Application\Message\Template\DTO\TemplateDTO;
use App\Application\Message\Template\Transformer\ArrayToTemplatesTransformer;
use App\Application\Message\Template\Transformer\ArrayToUsersTransformer;
use App\Domain\Message\Template\Service\TemplateAdapter;
use App\Domain\Message\Template\ValueObject\Template;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class MessageTemplateController extends AbstractController
{
    private LoggerInterface $logger;
    private FlashBagInterface $flashBag;

    public function __construct(LoggerInterface $logger, FlashBagInterface $flashBag)
    {
        $this->logger = $logger;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route(
     *     path="/message/templates/create",
     *     methods={"GET"},
     *     name="message.templates.create.get",
     *     defaults={"security" = "yes"}
     *     )
     */
    public function createNewTemplateForm(Request $request, TemplateAdapter $templateAdapter): Response
    {
        return $this->render('message/template/create.html.twig');
    }

    /**
     * @Route(
     *     path="/message/templates",
     *     methods={"POST"},
     *     name="message.templates.create.post",
     *     defaults={"security" = "yes"}
     *     )
     */
    public function createNewTemplate(Request $request, TemplateAdapter $templateAdapter): Response
    {
        try {
            $template = new Template(
                $request->get('name'),
                $request->get('lang'),
                $request->get('type'),
                $request->get('subject'),
                $request->get('context'),
            );
            $response = $templateAdapter->createTemplate($template);
            if ($response->isSuccess()) {
                $this->flashBag->add('success', 'web.message.templates.create.success');
            }

            if ($response->hasError()) {
                $this->flashBag->add('error', 'web.message.templates.create.error.code' . $response->getErrorCode());
            }
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.message.templates.create.error.code.' . $exception->getCode());
        }

        return $this->redirectToRoute('message.templates.get');
    }

    /**
     * @Route(path="/message/templates", methods={"GET"}, name="message.templates.get", defaults={"security" = "yes"})
     */
    public function getAllTemplates(TemplateAdapter $templateAdapter): Response
    {
        try {
            $response = $templateAdapter->getAllTemplates();
            $templates = ArrayToTemplatesTransformer::transform($response->getData());

            return $this->render('message/template/list.html.twig', ['templates' => $templates]);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.message.templates.get.error.code.' . $exception->getCode());

            return $this->redirectToRoute('web.index');
        }
    }

    /**
     * @Route(
     *     path="/message/templates/{id}",
     *     methods={"GET"},
     *     name="message.template.get",
     *     defaults={"security" = "yes"}
     *     )
     */
    public function getOneTemplate(string $id, TemplateAdapter $templateAdapter): Response
    {
        try {
            $response = $templateAdapter->getTemplateById($id);
            $template = new TemplateDTO($response->getData());

            return $this->render('message/template/item.html.twig', ['template' => $template]);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.message.template.get.error.code.' . $exception->getCode());

            return $this->redirectToRoute('message.templates.get');
        }
    }

    /**
     * @Route(
     *     path="/message/templates/{id}",
     *     methods={"POST"},
     *     name="message.template.update",
     *     defaults={"security" = "yes"}
     *     )
     */
    public function updateTemplate(string $id, Request $request, TemplateAdapter $templateAdapter): RedirectResponse
    {
        try {
            $templateAdapter->updateTemplateById($id, $request->request->all());

            return $this->redirectToRoute('message.template.get', ['id' => $id]);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.message.template.update.error.code.' . $exception->getCode());

            return $this->redirectToRoute('message.templates.get');
        }
    }

    /**
     * @Route(
     *     path="/message/templates/delete/{id}",
     *     methods={"GET"},
     *     name="message.template.delete",
     *     defaults={"security" = "yes"}
     *     )
     */
    public function deleteTemplate(string $id, TemplateAdapter $templateAdapter): RedirectResponse
    {
        try {
            $templateAdapter->deleteTemplateById($id);
        } catch (Throwable $exception) {
            $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.message.template.delete.error.code.' . $exception->getCode());
        }

        return $this->redirectToRoute('message.templates.get');
    }
}
