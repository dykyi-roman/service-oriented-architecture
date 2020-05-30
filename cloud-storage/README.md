Cloud storage service
=======

![image](docs/architecture.png)

## Endpoints

| Path                    | Method  | Scheme | Grant |
| ----------------------  | ------- | ------ | ----- |
| /api/storage/folder     | POST    | ANY    | ALL   |
| /api/storage/upload     | POST    | ANY    | ALL   |
| /api/storage/download   | GET     | ANY    | ALL   |
| /api/storage/delete     | DELETE  | ANY    | ALL   |

## Requirements

* PHP 7.4
* Lumen
* Redis

## Adapters

| Name         | Class                      |
| -----------  | -------------------------  |
| drop-box     | \DropBoxAdapter::class     |
| aws          | \AwsAdapter::class         |
| in-memory    | \InMemoryAdapter::class    |
| file-storage | \FileStorageAdapter::class |
| google-drive | \GoogleDriveAdapter::class |

### Your self Adapter

If you did not find the adapter hat you need in the list, you can easy implement it yourself.
To do this, you need to:

1) create a file `/Infrastructure/Adapters/XXXAdapter.php`, where XXX it is the name of your new adapter
2) `XXXAdapter` should be inherit from the interface `StorageInterface`

For use your new adapter just update `APP_ADAPTERS=XXX` key in `.env` file.

Enjoy it!

### File Storage Adapter

You need to give a permission to storage dir:

```
sudo chmod -R 777 ./code/storage
```

### Google Drive Adapter

[PHP Quickstart](https://developers.google.com/drive/api/v3/quickstart/php) - complete the steps described in the rest of this page to create a simple PHP command-line application that makes requests to the Drive API.

Create your Google Drive API keys. Here a detailed information on how to obtain your API ID, secret and refresh token:

-   [Getting your Client ID and Secret](README/1-getting-your-dlient-id-and-secret.md)
-   [Getting your Refresh Token](README/2-getting-your-refresh-token.md)
-   [Getting your Root Folder ID](README/3-getting-your-root-folder-id.md)

You also need to give a permission to resource dir:

```
sudo chmod -R 777 ./code/resources
```

### S3 Adapter

[Getting Started with the AWS SDK for PHP](https://aws.amazon.com/ru/articles/getting-started-with-the-aws-sdk-for-php/?tag=articles%23keywords%23amazon-s3) - this guide shows you how to start building PHP applications on the Amazon Web Services platform with the AWS SDK for PHP.

You also need to to create a [Bucket](https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingBucket.html) 
and make sure that your bucket is configured for clients to set a public-accessible ACL.

### DropBox Adapter

[Community SDKs](https://www.dropbox.com/developers/documentation/communitysdks) - the guide to implementation of Dropbox API.

To start working with the Dropbox API, you'll need an App. You can create a new app for the Dropbox API [here](https://www.dropbox.com/developers/apps)
 
## Clean code

```
make pre-commit
```
 
## Ressources:

[Dropbox develop apps](https://www.dropbox.com/developers)

[Dropbox Community SDKs](https://www.dropbox.com/developers/documentation/communitysdks)

[Drive API > PHP Quickstart](https://developers.google.com/drive/api/v3/quickstart/php)

[Laravel google drive demo](https://github.com/ivanvermeyen/laravel-google-drive-demo)

[Nginx content caching](https://docs.nginx.com/nginx/admin-guide/content-cache/content-caching/)
    
## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)

