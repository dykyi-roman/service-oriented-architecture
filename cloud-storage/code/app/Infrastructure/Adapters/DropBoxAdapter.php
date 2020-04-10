<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Exception\FileStorageException;
use App\Domain\FileStorageInterface;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Psr\Log\LoggerInterface;

final class DropBoxAdapter implements FileStorageInterface
{
    private Dropbox $client;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->client = new Dropbox(
            new DropboxApp(
                env('DROPBOX_API_KEY'),
                env('DROPBOX_API_SECRET'),
                env('DROPBOX_TOKEN')
            )
        );
    }

    public function createFolder(string $name): void
    {
        try {
            $this->client->createFolder('/' . ltrim($name, '/'));
        } catch (DropboxClientException $exception) {
            $error = $exception->getMessage();
            $this->logger->error('CloudStorage:DropBoxAdapter', ['method' => __FUNCTION__, 'error' => $error]);

            throw FileStorageException::createFolderProblem($name);
        }
    }
}
