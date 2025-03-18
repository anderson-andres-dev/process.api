<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/process.api.test');
$app->addErrorMiddleware(true, true, true);

#Cargar Rutas
foreach (glob(__DIR__ . '/routes/*.php') as $routeFile) {
    $route = require $routeFile;
    $route($app);
}

$app->run();