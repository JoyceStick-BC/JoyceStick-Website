<?php

use Respect\Validation\Validator as v;

session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
		'determineRouteBeforeAppMiddleware' => true,
	    'addContentLengthHeader' => false,
		'db' => [
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => '',
			'username' => '',
			'password' => '',
			'collation' => 'latin1_swedish_ci',
			'prefix' => ''
		]
	]
]);

$container = $app->getContainer();

//set up eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

//Add Db to container
$container['db'] = function ($container) use ($capsule) {
	return $capsule;
};

$container['auth'] = function($container) {
	return new \Carbon\Auth\Auth;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};

$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
		'cache' => false
	]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));

	$view->getEnvironment()->addGlobal('auth', [
		'check' => $container->auth->check(),
		'user' => $container->auth->user()
	]);

	$view->getEnvironment()->addGlobal('flash', $container->flash);

	return $view;
};

$container['validator'] = function($container) {
	return new \Carbon\Validation\Validator;
};

$container['ContactController'] = function($container) {
	return new \Carbon\Controllers\ContactController($container);
};

$container['GalleryController'] = function($container) {
	return new \Carbon\Controllers\GalleryController($container);
};

$container['HomeController'] = function($container) {
	return new \Carbon\Controllers\HomeController($container);
};

$container['BlogController'] = function($container) {
	return new \Carbon\Controllers\BlogController($container);
};

$container['AuthController'] = function($container) {
	return new \Carbon\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function ($container) {
    return new \Carbon\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function($container) {
	return new \Slim\Csrf\Guard;
};

//middle ware
$app->add(new \Carbon\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \Carbon\Middleware\OldInputMiddleware($container));
$app->add(new \Carbon\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

v::with('Carbon\\Validation\\Rules');

require __DIR__ . '/../app/routes.php';