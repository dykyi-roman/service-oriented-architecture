<?php

$router->group(['prefix' => 'api/'], static function ($router) {
    $router->post('storage/folder/create', 'FileStorageController@createFolder');
    $router->post('storage/upload', 'FileStorageController@uploadFile');
    $router->get('storage/download', 'FileStorageController@download');
    $router->post('storage/delete', 'FileStorageController@delete');
});
