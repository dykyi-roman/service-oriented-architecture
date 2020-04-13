<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageAdapterInterface;
use App\Domain\ValueObject\UploadFile;
use Google_Client;
use Google_Exception;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;

/**
 * @see https://developers.google.com/oauthplayground/
 */
final class GoogleDriveAdapter implements StorageAdapterInterface
{
    private Google_Service_Drive $client;

    /**
     * @inheritDoc
     * @throws Google_Exception
     */
    public function __construct()
    {
        $client = $this->getClient(sprintf('%s/google/', resource_path()));
        $this->client = new Google_Service_Drive($client);
    }

    public function createFolder(string $name): array
    {
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($name);
        $file->setMimeType('application/vnd.google-apps.folder');
        $result = $this->client->files->create($file);

        return ['id' => $result->getId()];
    }

    public function upload(UploadFile $uploadFile): array
    {
        $fileMetadata = new Google_Service_Drive_DriveFile();
        $fileMetadata->setName($uploadFile->fileName());
        if (!$uploadFile->isRootUploadDir()) {
            $fileMetadata->setParents([$uploadFile->fileDir()]);
        }

        $result = $this->client->files->create($fileMetadata, [
            'data' => file_get_contents($uploadFile->file()),
            'uploadType' => 'multipart',
            'fields' => 'id, webContentLink, webViewLink'
        ]);

        if ($result->getId()) {
            $permission = new Google_Service_Drive_Permission(['type' => 'anyone', 'role' => 'reader']);
            $this->client->permissions->create($result->getId(), $permission, ['fields' => 'id']);

            return [
                'id' => $result->getId(),
                'name' => $uploadFile->fileName(),
                'url' => $this->prepareWebLink($result->getWebContentLink())
            ];
        }

        return [];
    }

    public function download(string $path): array
    {
        $response = $this->client->files->get($path, ['fields' => 'id, webContentLink, webViewLink']);
        if ($response->getId()) {
            return ['url' => $response->getWebContentLink()];
        }

        return [];
    }

    public function delete(string $path): array
    {
        $this->client->files->delete($path);

        return [];
    }

    /**
     * @inheritDoc
     * @throws Google_Exception
     */
    private function getClient(string $credentialsDir): Google_Client
    {
        $client = new Google_Client();
        $client->setApplicationName('SOA Drive API');
        $client->setScopes(Google_Service_Drive::DRIVE);
        $client->setAuthConfig($credentialsDir . 'credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $tokenPath = $credentialsDir . 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true, 512, JSON_THROW_ON_ERROR);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $accessToken = $client->fetchAccessTokenWithRefreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
                $client->setAccessToken($accessToken);
                if (array_key_exists('error', $accessToken)) {
                    throw new Google_Exception(implode(', ', $accessToken));
                }
            }

            file_put_contents($tokenPath, json_encode($client->getAccessToken(), JSON_THROW_ON_ERROR, 512));
        }

        return $client;
    }

    private function prepareWebLink(string $webContentLink): string
    {
        $url = explode('&', $webContentLink);
        array_pop($url);

        return implode('&', $url);
    }
}
