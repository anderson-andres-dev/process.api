<?php

namespace App\Process\BD;

use PDO;
use PDOException;

class DB {
    private static $pdo;

    // Método que inicializa la conexión a la base de datos
    public static function init(Connection $connection) {
        self::$pdo = $connection->getConnection();
    }

    // Método SELECT, recibe columnas, tabla y condiciones opcionales
    public static function SELECT(array $columns, string $table, array $where = []) {
        $cols = implode(', ', $columns);
        $sql = "SELECT $cols FROM $table";

        if (!empty($where)) {
            $conditions = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));
            $sql .= " WHERE $conditions";
        }

        return self::query($sql, array_values($where));
    }

    // Método SELECT_ALL, devuelve todas las filas de una tabla
    public static function SELECT_ALL(string $table) {
        return self::SELECT(['*'], $table);
    }

    // Método INSERT, inserta datos en una tabla
    public static function INSERT(string $table, array $data) {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        self::query($sql, array_values($data));

        return self::$pdo->lastInsertId();
    }

    // Método UPDATE, actualiza datos en una tabla
    public static function UPDATE(string $table, array $data, array $where) {
        $set = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $conditions = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));

        $sql = "UPDATE $table SET $set WHERE $conditions";

        return self::query($sql, array_merge(array_values($data), array_values($where)));
    }

    // Método DELETE, elimina filas de una tabla
    public static function DELETE(string $table, array $where) {
        $conditions = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));
        $sql = "DELETE FROM $table WHERE $conditions";

        return self::query($sql, array_values($where));
    }

    // Método RAW, ejecuta una consulta SQL personalizada
    public static function RAW(string $sql, array $params = []) {
        return self::query($sql, $params);
    }

    // Método CALL_VIEW, ejecuta una vista (consulta SELECT)
    public static function CALL_VIEW(string $viewName, array $params = []) {
        $placeholders = implode(', ', array_fill(0, count($params), '?'));
        $sql = "SELECT * FROM $viewName" . (empty($params) ? '' : " WHERE $placeholders");

        return self::query($sql, $params);
    }

    // Método privado que ejecuta una consulta SQL
    private static function query(string $sql, array $params = []) {
        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
