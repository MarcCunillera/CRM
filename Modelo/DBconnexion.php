<?php
// Modelo/DBconnexion.php

require_once __DIR__ . '/../Configuracion/ConfiguracionBD.php';

class Database {
    public static function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $conn = new PDO($dsn, DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
