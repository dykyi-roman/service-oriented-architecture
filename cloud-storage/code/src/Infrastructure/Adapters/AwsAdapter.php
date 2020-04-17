<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\StorageInterface;
use App\Domain\ValueObject\StorageResponse;
use App\Domain\ValueObject\UploadFile;
use Aws\S3\S3Client;

/**
 * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html
 * @see https://aws.amazon.com/articles/getting-started-with-the-aws-sdk-for-php/?tag=articles%23keywords%23amazon-s3
 */
final class AwsAdapter implements StorageInterface
{
    private S3Client $client;

    public function __construct()
    {
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_REGION')
        ]);
    }

    public function createFolder(string $name): StorageResponse
    {
        $this->client->putObject([
            'Bucket' => env('AWS_BUCKET_NAME'),
            'Key' => $this->addDelimiter($name),
            'Body' => '',
            'ACL' => 'public-read'
        ]);

        return StorageResponse::createById($name);
    }

    public function upload(UploadFile $uploadFile): StorageResponse
    {
        $dir = $uploadFile->isRootUploadDir() ? '' : $this->addDelimiter($uploadFile->fileDir());
        $path = $dir . $uploadFile->fileName();

        $this->client->putObject([
            'Bucket' => env('AWS_BUCKET_NAME'),
            'Key' => $path,
            'Body' => file_get_contents($uploadFile->file()),
            'ACL' => 'public-read'
        ]);

        $url = sprintf('s3://%s/%s', env('AWS_BUCKET_NAME'), $path);

        return StorageResponse::create('', $path, $url);
    }

    public function download(string $filePath): StorageResponse
    {
        $url = $this->client->getObjectUrl(env('AWS_BUCKET_NAME'), $filePath);

        return StorageResponse::createByUrl($url);
    }

    public function delete(string $path): StorageResponse
    {
        $this->client->deleteObject([
            'Bucket' => env('AWS_BUCKET_NAME'),
            'Key' => $this->addDelimiter($path),
        ]);

        return StorageResponse::empty();
    }

    private function addDelimiter(string $value): string
    {
        return rtrim($value, '/') . '/';
    }
}
