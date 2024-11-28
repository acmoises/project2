<?php

namespace Traits;

trait DatabaseTrait{

    public function getConnection() {
        $dbHost = 'localhost';
        $dbName = 'project';
        $dbUser = 'root';
        $dbPass = '';

        try {
            $dsn = "mysql:host=" . ($dbHost ?? '') . ";dbname=" . ($dbName ?? '') . ";charset=utf8mb4";
            $db = new \PDO($dsn, $dbUser ?? '', $dbPass ?? '');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (\PDOException $e) {
            echo "Error: No se pudo conectar a la base de datos.";
            echo "Detalles del error: " . $e->getMessage();
            return null;
        }
    }

}
