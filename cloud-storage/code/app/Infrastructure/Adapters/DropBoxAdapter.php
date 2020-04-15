<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageInterface;
use App\Domain\ValueObject\StorageResponse;
use App\Domain\ValueObject\UploadFile;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;

/**
 * @see https://github.com/kunalvarma05/dropbox-php-sdk/wiki/Configuration
 * @see https://www.dropbox.com/developers/documentation/http/documentation#sharing-get_shared_link_metadata
 */
final class DropBoxAdapter implements StorageInterface
{
    private const USER_CONTENT = 'dl.dropboxusercontent.com';

    private Dropbox $client;

    public function __construct()
    {
        $this->client = new Dropbox(
            new DropboxApp(
                env('DROPBOX_API_KEY'),
                env('DROPBOX_API_SECRET'),
                env('DROPBOX_TOKEN')
            )
        );
    }

    /**
     * @inheritDoc
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function createFolder(string $name): StorageResponse
    {
        $folder = $this->client->createFolder($this->addDelimiter($name));

        return StorageResponse::createById($folder->getId());
    }

    public function upload(UploadFile $uploadFile): StorageResponse
    {
        $file = new DropboxFile($uploadFile->file());
        $dir = $uploadFile->isRootUploadDir() ? '/' : $this->addDelimiter($uploadFile->fileDir()) . '/';
        $path = $dir . $uploadFile->fileName();
        $result = $this->client->upload($file, $path, ['autorename' => true]);
        if ($result) {
            $response = $this->client->postToAPI('/sharing/create_shared_link_with_settings', [
                'path' => $path,
                'settings' => [
                    'requested_visibility' => 'public',
                    'audience' => 'public',
                    'access' => 'viewer'
                ]
            ]);

            $url = '';
            $body = $response->getDecodedBody();
            if (array_key_exists('url', $body)) {
                $url = str_replace('dropbox.com', self::USER_CONTENT, $body['url']);
            }

            return StorageResponse::create($result->getId(), $path, $url);
        }

        return StorageResponse::empty();
    }

    /**
     * @inheritDoc
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function download(string $path): StorageResponse
    {
        $response = $this->client->postToAPI('/sharing/list_shared_links', ['path' => $path]);
        $body = $response->getDecodedBody();
        if (0 === count($body['links'])) {
            return StorageResponse::empty();
        }

        $url = rtrim($body['links'][0]['url'], '0') . 1;

        return StorageResponse::createByUrl($url);
    }

    /**
     * @inheritDoc
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function delete(string $path): StorageResponse
    {
        $this->client->delete($this->addDelimiter($path));

        return StorageResponse::empty();
    }

    private function addDelimiter(string $value): string
    {
        return '/' . ltrim($value, '/');
    }
}
