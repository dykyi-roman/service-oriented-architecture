<?php

declare(strict_types=1);

namespace App\UI\Http\Web;

use App\Application\Common\Exception\LoggerException;
use App\Domain\Storage\DTO\FileDTO;
use App\Domain\Storage\StorageAdapter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class StorageController extends AbstractController
{
    private LoggerInterface $logger;
    private FlashBagInterface $flashBag;

    public function __construct(LoggerInterface $logger, FlashBagInterface $flashBag)
    {
        $this->logger = $logger;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route(path="/user/storage/search", name="storage.files.search.get", defaults={"security" = "yes"})
     */
    public function searchFiles(): Response
    {
        return $this->render('storage/search.html.twig');
    }

    /**
     * @Route(path="/user/{userId}/storage/files", name="storage.files.get", defaults={"security" = "yes"})
     */
    public function getFiles(string $userId, StorageAdapter $storageAdapter): Response
    {
        try {
            $response = $storageAdapter->searchFilesByUserId($userId);
            $files = array_map((fn(array $file) => new FileDTO($file)), $response->getData());

            return $this->render('storage/list.html.twig', ['files' => $files]);
        } catch (Throwable $exception) {
            $this->logger->error(...LoggerException::log(__METHOD__, $exception->getMessage()));
            $this->flashBag->add('error', 'web.storage.files.get.error.code.' . $exception->getCode());

            return $this->redirectToRoute('web.index');
        }
    }
}
