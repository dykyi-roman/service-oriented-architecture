<?php

$router->group(['prefix' => 'api/', 'middleware' => ['auth-verify']], static function ($router) {
    $router->post('storage/folder', ['uses' => 'StorageController@createFolder', 'as' => 'api.storage.folder.create']);
    $router->post('storage/file', ['uses' => 'StorageController@uploadFile', 'as' => 'api.storage.upload']);
    $router->get('storage/file', ['uses' => 'StorageController@download', 'middleware' => ['cache-control'], 'as' => 'api.storage.download']);
    $router->delete('storage/file', ['uses' => 'StorageController@delete', 'as' => 'api.storage.delete']);
});
