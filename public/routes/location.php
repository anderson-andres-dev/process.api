<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\Process\Classes\Location;

return function (App $app) {
    $app->get('/paises', function (Request $request, Response $response) {
        $paises = (new Location())->getPaises();
        $response->getBody()->write(
            json_encode($paises, JSON_UNESCAPED_UNICODE)
        );
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/provincias/{id_pais}', function (Request $request, Response $response, array $args) {
        $paises = (new Location())->getProvincias($args['id_pais']);
        $response->getBody()->write(
            json_encode($paises, JSON_UNESCAPED_UNICODE)
        );
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/cantones/{id_provivncia}', function (Request $request, Response $response, array $args) {
        $paises = (new Location())->getCantones($args['id_provivncia']);
        $response->getBody()->write(
            json_encode($paises, JSON_UNESCAPED_UNICODE)
        );
        return $response->withHeader('Content-Type', 'application/json');
    });
};

