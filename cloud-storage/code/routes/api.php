<?php

$router->group(['prefix' => 'api/', 'middleware' => ['auth-verify']], static function ($router) {
    $router->post('user/{userId}/storage/folders', ['uses' => 'StorageController@createFolder', 'as' => 'api.storage.folder.create']);
    $router->post('user/{userId}/storage/files', ['uses' => 'StorageController@uploadFile', 'as' => 'api.storage.upload']);
    $router->get('user/{userId}/storage/files', ['uses' => 'StorageController@download', 'middleware' => ['cache-control'], 'as' => 'api.storage.download']);
    $router->delete('user/{userId}/storage/files', ['uses' => 'StorageController@delete', 'as' => 'api.storage.delete']);
});

$router->group(['prefix' => 'api/admin/', 'middleware' => ['auth-verify']], static function ($router) {
    $router->get('user/{userId}/storage/files', ['uses' => 'AdminController@files', 'as' => 'api.admin.storage.files']);
    $router->get('user/{userId}/storage/files/{id}', ['uses' => 'AdminController@file', 'as' => 'api.admin.storage.file']);
});
