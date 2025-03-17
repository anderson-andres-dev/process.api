<?php

namespace App\Process\Classes;

use App\Process\BD\Connection;
use PDO;

class Location
{

    public function getPaises(): string
    {

        $conn = new Connection();
        $pdo = $conn->getConnection();

        $sql = 'SELECT nombre, iso3 from paises';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
