<?php

$router->group(['prefix' => 'api/'], static function ($router) {
    $router->post('storage/folder/create', 'StorageController@createFolder');
    $router->post('storage/upload', 'StorageController@uploadFile');
    $router->get('storage/download', ['middleware' => 'cache-control', 'uses' => 'StorageController@download']);
    $router->post('storage/delete', 'StorageController@delete');
});
