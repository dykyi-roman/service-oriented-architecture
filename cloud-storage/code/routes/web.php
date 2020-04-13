<?php

$router->get('/', 'SwaggerController@index');
$router->get('/swagger/update', 'SwaggerController@update');
//
$router->get('/storage', 'WebController@storage');
$router->get('/download', 'WebController@download');

$router->get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
