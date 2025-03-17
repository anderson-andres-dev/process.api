<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Process\Classes\Hello;
use App\Process\BD\Connection;
use App\Process\Classes\Location;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/process.api.test');
$app->addErrorMiddleware(true, true, true);

$app->get('/hello/{nombre}', function (Request $request, Response $response, array $args) {
    
    $hello = new Hello();
    $json = $hello->saludar(nombre: $args['nombre']);

    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/paises', function (Request $request, Response $response, array $args) {
    $location = new Location();
    $paises = $location->getPaises();
    $response->getBody()->write($paises);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/test-db', function (Request $request, Response $response) {
    try {
        $db = new Connection();
        $pdo = $db->getConnection();

        // Obtener datos relevantes
        $stmt = $pdo->query("SELECT DATABASE() as db_name");
        $data = $stmt->fetch();

        $result = [
            'status' => 'success',
            'database_name' => $data['db_name'] ?? 'Unknown'
        ];
    } catch (PDOException $e) {
        $result = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();