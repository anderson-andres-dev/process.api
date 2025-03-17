<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Process\Classes\Hello;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/process.api.test');
$app->addErrorMiddleware(true, true, true);

$app->get('/hello/{nombre}', function (Request $request, Response $response, array $args) {
    
    $hello = new Hello();
    $json = $hello->saludar(nombre: $args['nombre']);

    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();