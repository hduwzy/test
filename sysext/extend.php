<?php
use flight\Flight;

$app = Flight::app();

$app->register('router', '\sysext\net\Router', array(), function ($obj) use ($app) {
	$obj->app = $app;
});

$app->register('request', '\sysext\net\Request', array(), function ($obj) use ($app) {
	$obj->app = $app;
});

$app->register('conf', '\sysext\app\Conf', array(), function ($obj) use ($app) {
	$obj->app = $app;
});


$app->register('cookie', '\sysext\net\Cookie', array(), function ($obj) use ($app) {
	$obj->app = $app;
});

$app->register('db', 'sysext\db\Mysql', array(), function ($obj) use($app) {
	$obj->app = $app;
});


$app->map('start', function() use ($app){
	$request = $app->request();
	$response = $app->response();
	$router = $app->router();
	if (ob_get_length() > 0) {
		$response->write(ob_get_clean());
	}

	ob_start();

	$app->handleErrors($app->get('flight.handle_errors'));
	
	if ($request->ajax) {
        $response->cache(false);
    }

    $app->after('start', function() use ($app) {
        $app->stop();
    });

    $callback = $router->route($request);

    if (!is_callable($callback)) {
    	$app->notFound();
    }

    $callback();
});

$app->map('route', function () use ($app) {
	$app->router()->route($app->request());
});

unset($app);