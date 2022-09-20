<?php

require __DIR__ . '/DB.php'; //require DB class
require __DIR__ . '/Router.php'; //require Router class
require __DIR__ . '/../routes.php'; //require routes.php to indicate which file to server in case of which URI
require __DIR__ . '/../config.php'; //require basic config for this application

$router = new Router;
$router->setRoutes($routes);

$url = $_SERVER['REQUEST_URI'];

require __DIR__ . "/../api/" . $router->getFilename($url);

