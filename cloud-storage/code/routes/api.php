<?php

$router->group(['prefix' => 'api/'], static function ($router) {
    $router->post('storage/folder/create', 'FileStorageController@createFolder');
    //    $app->put('todo/{id}/', 'TodoController@update');
    //    $app->delete('todo/{id}/', 'TodoController@delete');
});
