<?php

use BirthFramework\Request;
use BirthFramework\Router;

include __DIR__ . '/../config.php';

$request = Request::getInstance();

$router = Router::getInstance();

include  APP_PATH . '/routes.php';

$container = $router->exec();

$response = $container->exec();

$response->send();

exit();
