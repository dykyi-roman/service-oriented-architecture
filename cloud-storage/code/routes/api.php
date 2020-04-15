<?php

$router->group(['prefix' => 'api/'], static function ($router) {
    $router->post('storage/folder', 'StorageController@createFolder');
    $router->post('storage/upload', 'StorageController@uploadFile');
    $router->get('storage/download', ['middleware' => 'cache-control', 'uses' => 'StorageController@download']);
    $router->delete('storage/delete', 'StorageController@delete');
});
