<?php

use App\Infrastructure\Cache\CacheInterface;
use App\Infrastructure\Cache\RedisCache;
use App\Infrastructure\Metrics\MetricsInterface;
use App\Infrastructure\Metrics\StatsDMetrics;
use App\Infrastructure\Secret\VaultClient;
use App\UI\Http\Middleware\CacheControlMiddleware;
use GuzzleHttp\Client;
use Sentry\Laravel\ServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Secrets
|--------------------------------------------------------------------------
*/
if (env('APP_ENV') !== 'local') {
    $secretClient = new VaultClient(new Client(), env('VAULT_HOST'), env('VAULT_TOKEN'));
    $secrets = $secretClient->read(sprintf('%s/%s', env('APP_ENV'), env('APP_NAME')));
    foreach ($secrets as $key => $value) {
        if (array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }
}

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// $app->withFacades();

// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Application\Exceptions\Handler::class,
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\UI\Console\Kernel::class
);

$app->singleton(MetricsInterface::class, static function () {
    return new StatsDMetrics(
        env('METRICS_HOST'),
        env('METRICS_PORT'),
        env('METRICS_NAMESPACE'),
        env('METRICS_TIMEOUT'),
    );
});

$app->singleton(CacheInterface::class, static function () {
    return new RedisCache(
        env('REDIS_HOST'),
        env('REDIS_PORT'),
    );
});

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');
$app->configure('logging');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    CacheControlMiddleware::class
]);

$app->routeMiddleware([
    'cache-control' => CacheControlMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(ServiceProvider::class);
// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\UI\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
    require __DIR__ . '/../routes/api.php';
});

return $app;
