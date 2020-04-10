<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Exception\FileStorageException;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Psr\Log\LoggerInterface;

final class DropBoxAdapter extends AbstractAdapter implements StorageAdapterInterface
{
    public const ADAPTER = __CLASS__;

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
            $this->client->createFolder($this->addDelimiter($name));
        } catch (DropboxClientException $exception) {
            $error = $exception->getMessage();
            $this->logger->error('CloudStorage:DropBoxAdapter', ['method' => __FUNCTION__, 'error' => $error]);

            throw FileStorageException::createFolderProblem($name);
        }
    }

    public function delete(string $path): void
    {
        try {
            $this->client->delete($path);
        } catch (DropboxClientException $exception) {
            $error = $exception->getMessage();
            $this->logger->error('CloudStorage:DropBoxAdapter', ['method' => __FUNCTION__, 'error' => $error]);

            throw FileStorageException::deleteProblem($path);
        }
    }

    public function upload(string $filePath, string $uploadFilePath): void
    {
        try {
            $file = new DropboxFile($filePath);
            $this->client->upload($file, $this->addDelimiter($uploadFilePath), ['autorename' => true]);
        } catch (DropboxClientException $exception) {
            $error = $exception->getMessage();
            $this->logger->error('CloudStorage:DropBoxAdapter', ['method' => __FUNCTION__, 'error' => $error]);

            throw FileStorageException::uploadProblem($uploadFilePath);
        }
    }

    public function download(string $filePath, string $downloadFilePath = null): string
    {
        try {
            $file = $this->client->download($filePath, $downloadFilePath);

            dump($file); die();
            return $file->getContents();
        } catch (DropboxClientException $exception) {
            $error = $exception->getMessage();
            $this->logger->error('CloudStorage:DropBoxAdapter', ['method' => __FUNCTION__, 'error' => $error]);

            throw FileStorageException::downloadProblem($filePath, $downloadFilePath);
        }
    }

    private function addDelimiter(string $value): string
    {
        return '/' . ltrim($value, '/');
    }
}
