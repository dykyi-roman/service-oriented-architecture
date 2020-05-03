<?php

$router->group(['prefix' => 'api/', 'middleware' => ['auth-verify']], static function ($router) {
    $router->post('storage/folder', ['uses' => 'StorageController@createFolder', 'as' => 'api.storage.folder.create']);
    $router->post('storage/upload', ['uses' => 'StorageController@uploadFile', 'as' => 'api.storage.upload']);
    $router->get('storage/download', ['uses' => 'StorageController@download', 'middleware' => ['cache-control'], 'as' => 'api.storage.download']);
    $router->delete('storage/delete', ['uses' => 'StorageController@delete', 'as' => 'api.storage.delete']);
});
