<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\Process\BD\Connection;
use PDOException;

return function (App $app) {
    $app->get('/test', function (Request $request, Response $response) {
        try {
            $db = new Connection();
            $pdo = $db->getConnection();

            // Obtener nombre de la base de datos activa
            $stmt = $pdo->query("SELECT DATABASE() as db_name");
            $dbName = $stmt->fetchColumn() ?: 'Unknown';

            // Obtener versión del servidor MySQL/MariaDB
            $stmt = $pdo->query("SELECT VERSION() as version");
            $dbVersion = $stmt->fetchColumn() ?: 'Unknown';

            // Obtener usuarios conectados actualmente
            $stmt = $pdo->query("SELECT COUNT(*) as users FROM information_schema.processlist");
            $activeUsers = $stmt->fetchColumn() ?: 0;

            // Obtener tiempo de actividad del servidor
            $stmt = $pdo->query("SHOW GLOBAL STATUS LIKE 'Uptime'");
            $uptime = $stmt->fetch(PDO::FETCH_ASSOC);
            $serverUptime = $uptime['Value'] ?? 'Unknown';

            // Verificar estado de las tablas
            $stmt = $pdo->query("SHOW TABLE STATUS");
            $tables = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tables[] = [
                    'name' => $row['Name'],
                    'engine' => $row['Engine'],
                    'rows' => $row['Rows'],
                    'size_kb' => round($row['Data_length'] / 1024, 2),
                    'auto_increment' => $row['Auto_increment'] ?? null
                ];
            }

            // Resultado final
            $result = [
                'status' => 'success',
                'database_name' => $dbName,
                'database_version' => $dbVersion,
                'active_users' => $activeUsers,
                'server_uptime_seconds' => $serverUptime,
                'tables' => $tables
            ];
        } catch (PDOException $e) {
            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json');
    });
};

