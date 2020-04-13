<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\ValueObject\UploadFile;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use App\Domain\StorageAdapterInterface;

/**
 * @see https://github.com/kunalvarma05/dropbox-php-sdk/wiki/Configuration
 * @see https://www.dropbox.com/developers/documentation/http/documentation#sharing-get_shared_link_metadata
 */
final class DropBoxAdapter implements StorageAdapterInterface
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
    public function createFolder(string $name): array
    {
        $folder = $this->client->createFolder($this->addDelimiter($name));

        return ['id' => $folder->getId()];
    }

    public function upload(UploadFile $uploadFile): array
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

            return [
                'id' => $result->getId(),
                'name' => $uploadFile->fileName(),
                'url' => $url
            ];
        }

        return [];
    }

    /**
     * @inheritDoc
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function download(string $path): array
    {
        $response = $this->client->postToAPI('/sharing/list_shared_links', ['path' => $path]);
        $body = $response->getDecodedBody();
        if (0 === count($body['links'])) {
            return [];
        }

        $url = rtrim($body['links'][0]['url'], '0') . 1;

        return ['url' => $url];
    }

    /**
     * @inheritDoc
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function delete(string $path): array
    {
        $this->client->delete($this->addDelimiter($path));

        return [];
    }

    private function addDelimiter(string $value): string
    {
        return '/' . ltrim($value, '/');
    }
}
